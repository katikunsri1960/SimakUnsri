<?php

namespace App\Jobs\Kehadiran;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Batchable;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use Throwable;

class KehadiranDosenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public $tries = 5;  // Retry hingga 5 kali sebelum MaxAttemptsExceeded
    public $timeout = 300;  // 5 menit timeout per job (hindari infinite hang)
    public $backoff = [10, 30, 60, 120, 300];  // Exponential backoff: delay retry bertahap

    protected array $idnumbers;
    protected string $url;
    protected string $token;

    /**
     * Constructor.
     */
    public function __construct(array $idnumbers, string $batchId)
    {
        $this->idnumbers = $idnumbers;
        $this->url = config('services.moodle.ws_url');
        $this->token = config('services.moodle.ws_token');
        $this->withBatchId($batchId);
        $this->onQueue('kehadiran-dosen');
    }

    /**
     * Handle job (optimasi: Gunakan Guzzle Pool untuk concurrent controlled).
     */
    public function handle(): void
    {
        if ($this->batch()?->cancelled()) {
            Log::info('Job dibatalkan oleh batch', ['batch_id' => $this->batchId]);
            return;
        }

        Log::info('Memulai job kehadiran dosen (anti-hang)', [
            'batch_id' => $this->batchId,
            'idnumbers_count' => count($this->idnumbers),
        ]);

        $client = new Client([
            'timeout' => 30,  // Timeout per HTTP request
            'connect_timeout' => 10,
        ]);

        // Pool untuk fetch courses: Concurrent tapi dibatasi 3 (hindari overload Moodle/DB)
        $requests = function () use ($client) {
            foreach ($this->idnumbers as $idnumber) {
                $params = [
                    'wstoken' => $this->token,
                    'wsfunction' => 'core_course_get_courses_by_field',
                    'moodlewsrestformat' => 'json',
                    'field' => 'idnumber',
                    'value' => $idnumber,
                ];
                yield function () use ($client, $params, $idnumber) {
                    return $client->getAsync($this->url, ['query' => $params])
                        ->then(function ($response) use ($idnumber) {
                            if ($response->getStatusCode() === 200) {
                                $body = json_decode($response->getBody(), true);
                                $courses = $body['courses'] ?? [];
                                foreach ($courses as $course) {
                                    $this->processCourse($course, $idnumber);
                                }
                                Log::info('Berhasil fetch courses', ['idnumber' => $idnumber, 'courses_count' => count($courses)]);
                            } else {
                                Log::error('Gagal fetch courses (HTTP)', [
                                    'idnumber' => $idnumber,
                                    'status' => $response->getStatusCode(),
                                    'body' => $response->getBody()->getContents(),
                                ]);
                            }
                        })
                        ->otherwise(function (Throwable $e) use ($idnumber) {
                            Log::error('Exception fetch courses', [
                                'idnumber' => $idnumber,
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                            ]);
                        });
                };
            }
        };

        $pool = new Pool($client, $requests(), [
            'concurrency' => 3,  // Batasi 3 concurrent (kurangi DB load saat processCourse)
            'fulfilled' => function ($response, $index) {
                // Pool sudah handle then/otherwise di yield
            },
            'rejected' => function (Throwable $reason, $index) {
                $idnumber = $this->idnumbers[$index];
                Log::error('Pool rejected untuk idnumber', [
                    'idnumber' => $idnumber,
                    'error' => $reason->getMessage(),
                ]);
            },
        ]);

        // Jalankan pool
        $pool->promise()->wait();

        Log::info('Job kehadiran dosen selesai (anti-hang)', [
            'batch_id' => $this->batchId,
            'processed_idnumbers' => count($this->idnumbers),
        ]);
    }

    /**
     * Process course (sync dengan timeout, wrap DB di transaction).
     */
    private function processCourse(array $course, string $idnumber): void
    {
        if ($this->batch()?->cancelled()) return;

        $courseId = $course['id'] ?? null;
        if (!$courseId) {
            Log::warning('Course ID kosong', ['idnumber' => $idnumber]);
            return;
        }

        try {
            $response = Http::timeout(30)->get($this->url, [
                'wstoken' => $this->token,
                'wsfunction' => 'core_course_get_contents',
                'courseid' => $courseId,
                'moodlewsrestformat' => 'json',
            ]);

            if (!$response->successful()) {
                Log::error('Gagal fetch course contents', [
                    'idnumber' => $idnumber,
                    'course_id' => $courseId,
                    'status' => $response->status(),
                ]);
                return;
            }

            $contents = $response->json();

            $attendanceIds = [];
            foreach ($contents as $section) {
                foreach ($section['modules'] ?? [] as $module) {
                    if (($module['modname'] ?? null) === 'attendance') {
                        $attendanceIds[] = $module['instance'];
                    }
                }
            }

            // Process attendances secara serial untuk hindari overload
            foreach ($attendanceIds as $attendanceId) {
                $this->processAttendance($attendanceId, $course, $idnumber);
            }
        } catch (Throwable $e) {
            Log::error('Exception process course', [
                'idnumber' => $idnumber,
                'course_id' => $courseId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Process attendance (sync dengan timeout).
     */
    private function processAttendance(int $attendanceId, array $course, string $idnumber): void
    {
        if ($this->batch()?->cancelled()) return;

        try {
            $response = Http::timeout(30)->get($this->url, [
                'wstoken' => $this->token,
                'wsfunction' => 'mod_attendance_get_sessions',
                'attendanceid' => $attendanceId,
                'moodlewsrestformat' => 'json',
            ]);

            if (!$response->successful()) {
                Log::error('Gagal fetch attendance sessions', [
                    'idnumber' => $idnumber,
                    'attendance_id' => $attendanceId,
                    'status' => $response->status(),
                ]);
                return;
            }

            $sessions = $response->json();
            $this->handleSessions($sessions, $course, $idnumber);
        } catch (Throwable $e) {
            Log::error('Exception process attendance', [
                'idnumber' => $idnumber,
                'attendance_id' => $attendanceId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle sessions (optimasi: Bulk upsert + transaction + chunk).
     */
    private function handleSessions($sessions, array $course, string $idnumber): void
    {
        if (!is_array($sessions)) return;

        if ($this->batch()?->cancelled()) return;

        $courseId = $course['id'];
        $groups = $this->getGroups($courseId);
        $map = $this->mapGroupToKelas($groups);  // Sudah di-chunk di method ini

        $bulkData = [];  // Kumpul untuk bulk upsert
        foreach ($sessions as $session) {
            $groupId = $session['groupid'] ?? null;
            $kelas = $map[$groupId] ?? null;

            if (!$kelas || !$this->isValidSession($session)) continue;

            // ✅ Skip kalau attendance_log kosong
            if (empty($session['attendance_log'] ?? [])) {
                Log::info('Lewati session tanpa attendance_log', [
                    'idnumber' => $idnumber,
                    'session_id' => $session['id']
                ]);
                continue;
            }

            preg_match('/\d+/', strip_tags($session['description'] ?? ''), $matches);
            $sesi = $matches[0] ?? null;
            if (!$sesi) continue;  // Skip jika no sesi

            $now = now();
            $data = [
                'kode_mata_kuliah' => $idnumber,
                'nama_kelas' => $kelas['name'],
                'id_kelas_kuliah' => $kelas['id_kelas_kuliah'],
                'nama_mk' => $course['fullname'] ?? 'Nama MK tidak ditulis',
                'session_id' => $session['id'],
                'session_date' => $session['sessdate'],
                'deskripsi_sesi' => $sesi,
                'id_kehadiran' => $session['attendanceid'],
                'lasttaken' => $session['lasttaken'] ?? null,
                'timemodified' => $session['timemodified'] ?? null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $bulkData[] = $data;
        }

        // Bulk upsert dengan chunk jika terlalu banyak (hindari memory/DB hang)
        if (!empty($bulkData)) {
            $chunks = array_chunk($bulkData, 20);  // 20 per chunk
            foreach ($chunks as $chunk) {
                DB::transaction(function () use ($chunk) {
                    DB::table('kehadiran_dosen')->upsert(
                        $chunk,
                        ['session_id'],
                        ['deskripsi_sesi', 'updated_at']
                    );
                });
            }

            Log::info('Bulk upsert sessions selesai', [
                'idnumber' => $idnumber,
                'course_id' => $courseId,
                'sessions_count' => count($bulkData),
            ]);
        }
    }

    /**
     * Get groups (timeout + error handling).
     */
    private function getGroups(int $courseId): array
    {
        if ($this->batch()?->cancelled()) return [];

        try {
            $response = Http::timeout(30)->get($this->url, [
                'wstoken' => $this->token,
                'wsfunction' => 'core_group_get_course_groups',
                'courseid' => $courseId,
                'moodlewsrestformat' => 'json',
            ]);

            return $response->successful() ? $response->json() : [];
        } catch (Throwable $e) {
            Log::error('Gagal ambil groups', [
                'course_id' => $courseId,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }

    /**
     * Map group to kelas (optimasi: Chunk whereIn jika groups besar).
     */
    private function mapGroupToKelas(array $groups): array
    {
        $valid = array_filter($groups, fn($g) => !empty($g['idnumber']));
        if (empty($valid)) return [];

        $mapKelas = [];
        $idnumbersChunk = array_chunk(array_column($valid, 'idnumber'), 10);  // Chunk 10 untuk whereIn

        foreach ($idnumbersChunk as $chunk) {
            try {
                $chunkMap = DB::table('mk_kelas')
                    ->whereIn('kelas_kuliah', $chunk)
                    ->pluck('id_kelas_kuliah', 'kelas_kuliah')
                    ->toArray();
                $mapKelas = array_merge($mapKelas, $chunkMap);
            } catch (Throwable $e) {
                Log::error('Gagal chunk whereIn mk_kelas', [
                    'chunk_size' => count($chunk),
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $map = [];
        foreach ($valid as $g) {
            if (!isset($mapKelas[$g['idnumber']])) continue;
            $map[$g['id']] = [
                'name' => $g['idnumber'],
                'id_kelas_kuliah' => $mapKelas[$g['idnumber']]
            ];
        }

        return $map;
    }

    /**
     * Validasi session (lebih robust).
     */
    private function isValidSession(array $session): bool
    {
        $desc = trim(strip_tags($session['description'] ?? ''));
        if (empty($desc)) return false;

        // Harus ada angka sesi di description
        if (!preg_match('/\d+/', $desc)) return false;

        // Ambil sessdate (UNIX timestamp)
        $sessdate = intval($session['sessdate'] ?? 0);

        // Cutoff tanggal
        $cutoff = strtotime('2025-08-01');

        // Jika sessdate kosong atau lebih kecil dari cutoff, skip
        if ($sessdate < $cutoff) {
            Log::info('❌ Skip session karena sessdate < cutoff', [
                'session_id'    => $session['id'] ?? null,
                'sessdate_raw'  => $session['sessdate'] ?? null,
                'sessdate_human' => $sessdate ? date('Y-m-d H:i:s', $sessdate) : null,
                'cutoff'        => date('Y-m-d H:i:s', $cutoff),
            ]);
            return false;
        }
        return true;
    }

    /**
     * Handle job failure (log detail).
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Job kehadiran dosen gagal total setelah retry (DB hang?)', [
            'batch_id' => $this->batchId,
            'idnumbers_count' => count($this->idnumbers),
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
