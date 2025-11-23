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

                        if (json_last_error() !== JSON_ERROR_NONE) {
                            Log::error("JSON tidak valid untuk $idnumber", [
                                'body' => $response->body(),
                                'json_error' => json_last_error_msg(),
                            ]);
                            return;
                        }

                        $courses = $result['courses'] ?? [];

                        foreach ($courses as $course) {
                            $courseFullname = $course['fullname'] ?? 'Nama MK tidak ditulis';
                            $this->fetchAttendanceSessions($idnumber, $course, $courseFullname);
                        }
                    } else {
                        Log::error("Status code bukan 200 saat ambil course", [
                            'idnumber' => $idnumber,
                            'status_code' => $response->status(),
                            'body' => $response->body(),
                        ]);
                    }
                })
                ->otherwise(function ($error) use ($idnumber) {
                    Log::error("Gagal fetch data course", [
                        'idnumber' => $idnumber,
                        'exception' => $error instanceof \Exception ? $error->getMessage() : (string) $error,
                    ]);
                });
        }

        Utils::all($promises)->wait();
    }

    private function fetchAttendanceSessions($idnumber, $course, $courseFullname)
    {
        $attendanceIDs = kehadiran_dosen::pluck('id_kehadiran')->unique()->toArray();

        if (empty($attendanceIDs)) {
            Log::warning("No attendanceIDs found in the table kehadiran_dosen");
            return;
        }

        $batchSize = 100;
        $batches = array_chunk($attendanceIDs, $batchSize);

        foreach ($batches as $batchIndex => $batch) {
            $promises = [];
            $groups = $this->processGroup($course);

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

                            $afterTimestamp = strtotime('2025-08-01 00:00:00');

                            $filteredSessions = array_filter($sessions, function ($session) use ($groups, $afterTimestamp) {
                                $description = $session['description'] ?? '';
                                $sessdate = $session['sessdate'] ?? 0;

                                if (
                                    is_null($description) ||
                                    trim($description) === "" ||
                                    !preg_match('/\d/', $description) ||
                                    $sessdate < $afterTimestamp
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
                        } else {
                            Log::error("Failed to fetch sessions for attendanceID: {$attendanceID}", [
                                'status_code' => $sessionsResponse->status(),
                                'body' => $sessionsResponse->body(),
                            ]);
                        }
                    });
            }

            Utils::settle($promises)->wait();

            Log::info("Batch {$batchIndex} processed", [
                'batch_size' => count($batch),
                'attendance_ids' => $batch,
            ]);
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

        if (is_null($groupName)) {
            Log::info("Sesi dilewati karena groupId tidak sesuai", [
                'groupId' => $groupId,
                'idnumber' => $idnumber,
            ]);
            return;
        }

        $deskripsi_sesi = isset($session['description']) ? strip_tags($session['description']) : null;
        preg_match('/\d+/', $deskripsi_sesi, $matches);
        $deskripsi_sesi = $matches[0] ?? null;

        if (is_null($deskripsi_sesi) || !preg_match('/\d/', $deskripsi_sesi)) {
            Log::info("Deskripsi sesi tidak valid, dilewati.", [
                'session_id' => $session['id'],
            ]);
            return;
        }

        $data = [];
        foreach ($attendanceLogs as $log) {
            $studentid = $log['studentid'];
            $username = $this->getUsernameById($studentid);
            if (is_null($username)) {
                continue;
            }

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
                'status_mahasiswa'  => $statusDescriptions[$log['statusid']] ?? 'Unknown Status',
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
        if (!$courseId) {
            Log::warning("Course ID tidak ditemukan", ['course' => $course]);
            return [];
        }
        $groupParams = [
            'wstoken' => $this->token,
            'wsfunction' => 'core_group_get_course_groups',
            'courseid' => $courseId,
            'moodlewsrestformat' => 'json',
        ];
        $groupResponse = Http::withOptions(['verify' => false])->get($this->url, $groupParams);
        if (!$groupResponse->successful()) {
            Log::error("Gagal mendapatkan grup untuk Course ID $courseId", [
                'status_code' => $groupResponse->status(),
                'body' => $groupResponse->body(),
            ]);
            return [];
        }

        $groups = $groupResponse->json();
        // Ambil hanya grup valid yang punya idnumber
        $validGroups = array_filter($groups, fn($group) => !empty($group['idnumber']));
        // Ambil semua nama kelas dari tabel kehadiran_dosen
        $kelasList = [];
        DB::table('kehadiran_dosen')->orderBy('id')->chunk(1000, function ($rows) use (&$kelasList) {
            foreach ($rows as $row) {
                $kelasList[] = $row->nama_kelas;
            }
        });
        // Filter: hanya grup yang idnumber-nya cocok dengan nama_kelas
        $filteredGroups = array_filter($validGroups, function ($group) use ($kelasList) {
            return in_array($group['idnumber'], $kelasList);
        });
        // Reset array keys supaya rapi
        return array_values($filteredGroups);
    }

    private function getUsernameById($studentid)
    {
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
                $exists = RiwayatPendidikan::where('nim', $username)->exists();

                if ($exists) {
                    return $username;
                } else {
                    Log::warning("Username tidak ditemukan di database lokal", [
                        'student_id' => $studentid,
                        'username' => $username,
                    ]);
                }
            } else {
                Log::warning("Tidak ada user ditemukan di Moodle untuk student ID: $studentid");
            }
        } else {
            Log::error("Gagal mengambil user dari Moodle untuk student ID: $studentid", [
                'status_code' => $response->status(),
                'body' => $response->body()
            ]);
        }

        return null;
    }
}
