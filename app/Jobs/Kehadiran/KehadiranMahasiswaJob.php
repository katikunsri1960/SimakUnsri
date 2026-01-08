<?php

namespace App\Jobs\Kehadiran;

use App\Models\kehadiran_dosen;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Promise\Utils;

class KehadiranMahasiswaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected array $idnumbers;
    protected string $url;
    protected string $token;

    // [BARU] Property untuk menyimpan tanggal cutoff dan cache user
    protected int $cutoffDate;
    protected array $userCache = []; 

    public function __construct(array $idnumbers, string $batchId)
    {
        $this->url = config('services.moodle.ws_url');
        $this->token = config('services.moodle.ws_token');
        $this->idnumbers = $idnumbers;

        $this->withBatchId($batchId);
    }

    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        // --- [ AMBIL TANGGAL CUTOFF DARI DB] ---
        // Query ini hanya jalan 1x per Job
         $semesterAktif = DB::table('semester_aktifs')
            ->whereNotNull('krs_mulai')       // Pastikan datanya ada
            ->orderBy('krs_mulai', 'desc')    // Urutkan dari tanggal terbaru
            ->select('krs_mulai')
            ->first();

        if (!$semesterAktif || empty($semesterAktif->krs_mulai)) {
            Log::error('Job Mahasiswa berhenti: Data semester_aktif/krs_mulai tidak ditemukan.');
            return;
        }

        // Simpan ke property class
        $this->cutoffDate = strtotime($semesterAktif->krs_mulai);
        
        Log::info("Job Mahasiswa dimulai dengan cutoff: " . $semesterAktif->krs_mulai);
        // ---------------------------------------------

        $promises = [];

        foreach ($this->idnumbers as $idnumber) {
            $params = [
                'wstoken' => $this->token,
                'wsfunction' => 'core_course_get_courses_by_field',
                'moodlewsrestformat' => 'json',
                'field' => 'idnumber',
                'value' => $idnumber,
            ];

            $promises[] = Http::withOptions(['verify' => false])
                ->async()
                ->get($this->url, $params)
                ->then(function ($response) use ($idnumber) {
                    if ($response->successful()) {
                        $result = $response->json();
                        // ... validasi json error ...
                        if (json_last_error() !== JSON_ERROR_NONE) return;

                        $courses = $result['courses'] ?? [];

                        foreach ($courses as $course) {
                            $courseFullname = $course['fullname'] ?? 'Nama MK tidak ditulis';
                            $this->fetchAttendanceSessions($idnumber, $course, $courseFullname);
                        }
                    } else {
                        Log::error("Status code bukan 200 saat ambil course", ['idnumber' => $idnumber]);
                    }
                })
                ->otherwise(function ($error) use ($idnumber) {
                    Log::error("Gagal fetch data course: " . $error);
                });
        }

        Utils::all($promises)->wait();
    }

    private function fetchAttendanceSessions($idnumber, $course, $courseFullname)
    {
        // Optimasi: Cache attendance IDs agar tidak query berulang jika batch besar
        // (Tapi di sini pluck unique sudah cukup oke jika data tidak jutaan)
        $attendanceIDs = kehadiran_dosen::pluck('id_kehadiran')->unique()->toArray();

        if (empty($attendanceIDs)) {
            Log::warning("No attendanceIDs found in table kehadiran_dosen");
            return;
        }

        $batchSize = 50; // Turunkan sedikit batch size agar HTTP client tidak timeout
        $batches = array_chunk($attendanceIDs, $batchSize);

        // Ambil Groups SEKALI saja per course
        $groups = $this->processGroup($course);

        foreach ($batches as $batchIndex => $batch) {
            if ($this->batch()?->cancelled()) return;

            $promises = [];

            foreach ($batch as $attendanceID) {
                $sessionParams = [
                    'wstoken' => $this->token,
                    'wsfunction' => 'mod_attendance_get_sessions',
                    'attendanceid' => $attendanceID,
                    'moodlewsrestformat' => 'json',
                ];

                $promises[$attendanceID] = Http::withOptions(['verify' => false])
                    ->async()
                    ->get($this->url, $sessionParams)
                    ->then(function ($sessionsResponse) use ($attendanceID, $groups, $idnumber, $courseFullname) {
                        if ($sessionsResponse->successful()) {
                            $sessionsData = $sessionsResponse->json();
                            $sessions = $sessionsData['sessions'] ?? $sessionsData;

                            // --- [ FILTER TANGGAL DINAMIS] ---
                            $filteredSessions = array_filter($sessions, function ($session) use ($groups) {
                                $description = $session['description'] ?? '';
                                $sessdate = intval($session['sessdate'] ?? 0);

                                // Gunakan $this->cutoffDate yang diambil dari DB
                                if (
                                    empty($description) ||
                                    !preg_match('/\d/', $description) ||
                                    $sessdate < $this->cutoffDate 
                                ) {
                                    return false;
                                }

                                foreach ($groups as $group) {
                                    if (isset($session['groupid']) && $group['id'] == $session['groupid']) {
                                        return true;
                                    }
                                }
                                return false;
                            });

                            foreach ($filteredSessions as $session) {
                                $this->processSession($session, $idnumber, $courseFullname, $groups);
                            }
                        }
                    });
            }

            Utils::settle($promises)->wait();
        }
    }

    private function processSession($session, $idnumber, $courseFullname, $groups)
    {
        $attendanceLogs = $session['attendance_log'] ?? [];
        $groupId = $session['groupid'];
        $statuses = $session['statuses'] ?? [];

        $statusDescriptions = [];
        foreach ($statuses as $status) {
            $statusDescriptions[$status['id']] = $status['description'];
        }

        $groupName = null;
        foreach ($groups as $group) {
            if ($group['id'] == $groupId) {
                $groupName = $group['idnumber'];
                break;
            }
        }

        if (is_null($groupName)) return;

        $deskripsi_sesi = isset($session['description']) ? strip_tags($session['description']) : null;
        preg_match('/\d+/', $deskripsi_sesi, $matches);
        $deskripsi_sesi = $matches[0] ?? null;

        if (is_null($deskripsi_sesi)) return;

        $data = [];
        foreach ($attendanceLogs as $log) {
            $studentid = $log['studentid'];
            
            // [OPTIMASI] Gunakan method caching username
            $username = $this->getUsernameById($studentid); 
            
            if (is_null($username)) continue;

            $data[] = [
                'kode_mata_kuliah'  => $idnumber,
                'username'          => $username,
                'nama_kelas'        => $groupName,
                'nama_mk'           => $courseFullname,
                'session_id'        => $session['id'],
                'session_date'      => $session['sessdate'],
                'deskripsi_sesi'    => $deskripsi_sesi,
                'id_kehadiran'      => $session['attendanceid'],
                'status_id'         => $log['statusid'],
                'status_mahasiswa'  => $statusDescriptions[$log['statusid']] ?? 'Unknown',
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        if (!empty($data)) {
            DB::table('kehadiran_mahasiswa')->upsert(
                $data,
                ['username', 'session_id'],
                ['status_id', 'status_mahasiswa', 'updated_at']
            );
        }
    }

    private function processGroup($course)
    {
        $courseId = $course['id'] ?? null;
        if (!$courseId) return [];

        // 1. Ambil semua groups dari Moodle untuk course ini
        $groupResponse = Http::withOptions(['verify' => false])->get($this->url, [
            'wstoken' => $this->token,
            'wsfunction' => 'core_group_get_course_groups',
            'courseid' => $courseId,
            'moodlewsrestformat' => 'json',
        ]);

        if (!$groupResponse->successful()) return [];

        $groups = $groupResponse->json();
        $validGroups = array_filter($groups, fn($group) => !empty($group['idnumber']));

        if (empty($validGroups)) return [];

        // [OPTIMASI DATABASE]
        // Jangan ambil seluruh tabel kehadiran_dosen. Cukup cek apakah idnumber group ada di tabel tsb.
        $moodleGroupIds = array_column($validGroups, 'idnumber');
        
        $validKelasList = DB::table('kehadiran_dosen')
            ->whereIn('nama_kelas', $moodleGroupIds) // Cek hanya yg relevan
            ->distinct()
            ->pluck('nama_kelas')
            ->toArray();

        // Filter group Moodle yang hanya ada di DB Lokal
        $filteredGroups = array_filter($validGroups, function ($group) use ($validKelasList) {
            return in_array($group['idnumber'], $validKelasList);
        });

        return array_values($filteredGroups);
    }

    private function getUsernameById($studentid)
    {
        // [OPTIMASI CACHE MEMORY]
        // Cek dulu apakah student ini sudah pernah diambil di Job ini
        if (isset($this->userCache[$studentid])) {
            return $this->userCache[$studentid];
        }

        $response = Http::withOptions(['verify' => false])->get($this->url, [
            'wstoken' => $this->token,
            'wsfunction' => 'core_user_get_users',
            'moodlewsrestformat' => 'json',
            'criteria[0][key]' => 'id',
            'criteria[0][value]' => $studentid,
        ]);

        if ($response->successful()) {
            $result = $response->json();
            if (!empty($result['users'])) {
                $username = $result['users'][0]['username'];
                
                // Cek DB Lokal
                $exists = RiwayatPendidikan::where('nim', $username)->exists();

                if ($exists) {
                    // Simpan ke Cache dan return
                    $this->userCache[$studentid] = $username;
                    return $username;
                }
            }
        }
        
        // Simpan null ke cache supaya tidak direquest ulang kalau gagal/tidak ketemu
        $this->userCache[$studentid] = null;
        return null;
    }
}