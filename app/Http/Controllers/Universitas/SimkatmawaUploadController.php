<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\Simkatmawa\SimkatmawaUpload;
use App\Http\Controllers\Universitas\PdUnsriUploadController; // 1. Import PDUnsri Controller
use Illuminate\Support\Facades\Log;

class SimkatmawaUploadController extends Controller
{
    public function __construct()
    {
    }

    public function prestasi_mandiri()
    {
        $prodi = ProgramStudi::where('status', 'A')->orderBy('kode_program_studi')->get();
        $fakultas = Fakultas::orderBy('id')->get();

        return view('universitas.simkatmawa-upload.prestasi-mandiri', [
            'prodi' => $prodi,
            'fakultas' => $fakultas,
        ]);
    }

    public function prestasi_mandiri_data(Request $request)
    {
        $query = PrestasiMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.prodi'])
            ->where('penyelenggara', '!=', 'Belmawa') // Khusus Mandiri
            ->where('approved', 3)
            ->orWhere(function ($q) {
                $q->where('simkatmawa', 0)
                  ->where('pdunsri', 0);
            });                   // Sudah disetujui Ditmawa

        // Filter Prodi
        if ($request->filled('id_prodi') && $request->id_prodi != 'all') {
            $query->whereHas('riwayat_pendidikan', function ($q) use ($request) {
                $q->where('id_prodi', $request->id_prodi);
            });
        }

        $data = $query->orderBy('created_at', 'DESC')->get();

        $formattedData = $data->map(function ($item) {
            return [
                'id_prestasi'    => $item->id_prestasi_mahasiswa,
                'nim'            => optional($item->riwayat_pendidikan)->nim ?? '-',
                'nama_mahasiswa' => $item->nama_mahasiswa,
                'prodi'          => optional(optional($item->riwayat_pendidikan)->prodi)->nama_program_studi ?? '-',
                'nama_prestasi'  => $item->nama_prestasi,
                'penyelenggara'  => $item->penyelenggara,
                'tahun'          => $item->tahun_prestasi,
                'simkatmawa'     => $item->simkatmawa,
                'pdunsri'        => $item->pdunsri ?? 0,
                'status_sync'    => ($item->simkatmawa == 1 && ($item->pdunsri ?? 0) == 1) ? 'Sudah sync All' : 'Belum sync',
                'id_simkatmawa'  => $item->id_simkatmawa,
                'id_pdunsri'     => $item->id_pdunsri ?? null
            ];
        });

        return response()->json($formattedData);
    }

    public function prestasi_mandiri_upload(Request $request)
    {
        Log::info("==================================================");
        Log::info("START STREAMING PUSH PRESTASI MANDIRI (TERPISAH SIMKATMAWA & PDUNSRI)");
        Log::info("==================================================", [
            'id_prodi_filter' => $request->input('id_prodi', 'all')
        ]);

        $query = PrestasiMahasiswa::with(['riwayat_pendidikan'])
            ->where('penyelenggara', '!=', 'Belmawa')
            ->where('approved', 3)
            ->where(function ($q) {
                $q->where('simkatmawa', 0)
                  ->orWhere('pdunsri', 0);
            });

        if ($request->filled('id_prodi') && $request->id_prodi != 'all') {
            $query->whereHas('riwayat_pendidikan', function ($q) use ($request) {
                $q->where('id_prodi', $request->id_prodi);
            });
        }

        $data = $query->get();
        $totalData = $data->count();

        if ($totalData == 0) {
            Log::warning("PUSH PRESTASI MANDIRI: Tidak ada data yang perlu di-sync.");
            return response()->json(['error' => 'Data tidak ditemukan / Semua data sudah ter-sync'], 404);
        }

        Log::info("PUSH PRESTASI MANDIRI: Ditemukan {$totalData} data yang akan diproses.");

        // Inisialisasi statistik terpisah untuk SIMKATMAWA dan PDUNSRI
        $simkatmawaBerhasil = 0;
        $simkatmawaGagal    = 0;
        $pdunsriBerhasil    = 0;
        $pdunsriGagal       = 0;

        $pdUnsriController = new PdUnsriUploadController();

        $response = new StreamedResponse(function () use (
            $data, 
            $totalData, 
            &$simkatmawaBerhasil, 
            &$simkatmawaGagal, 
            &$pdunsriBerhasil, 
            &$pdunsriGagal, 
            $pdUnsriController
        ) {

            foreach ($data as $index => $d) {

                $nim = optional($d->riwayat_pendidikan)->nim ?? 'UNKNOWN';
                $idPrestasi = $d->id_prestasi_mahasiswa;

                Log::info("--- Processing Item [" . ($index + 1) . "/{$totalData}] ID: {$idPrestasi} | NIM: {$nim} ---");

                $fileSertifikat = $d->file_prestasi 
                    ? asset('storage/' . $d->file_prestasi) 
                    : 'http://www.test.com';

                $fileSuratTugas = $d->file_sk_tugas 
                    ? asset('storage/' . $d->file_sk_tugas) 
                    : $fileSertifikat;

                $arrayMahasiswa = [
                    [
                        'nim'  => $nim,
                        'nama' => strtoupper($d->nama_mahasiswa ?? '')
                    ]
                ];

                $arrayDosen = [];
                if (!empty($d->nidn_pembimbing)) {
                    $arrayDosen[] = [
                        'nuptk'            => (string) $d->nidn_pembimbing,
                        'nama'             => $d->nama_pembimbing ?? '',
                        'url_surat_tugas'  => $fileSuratTugas
                    ];
                }

                $recordSimkatmawa = [
                    'level'                 => 'NAS',
                    'kategori'              => 'OLAHRAGA',
                    'lomba'                 => $d->nama_prestasi,
                    'cabang'                => $d->kategori_kegiatan ?? 'Umum',
                    'penyelenggara'         => $d->penyelenggara,
                    'peringkat'             => $d->peringkat ?? 'JUARA1',
                    'jumlah_unit_peserta'   => (string) ($d->jumlah_peserta ?? '3'),
                    'kelompok_prestasi'     => $d->kelompok_prestasi ?? 'KELOMPOK',
                    'bentuk'                => $d->bentuk_kegiatan ?? 'DARING',
                    'url_peserta'           => $fileSuratTugas,
                    'url_sertifikat'        => $fileSertifikat,
                    'tgl_sertifikat'        => $d->tanggal_sk ?? date('Y-m-d', strtotime($d->created_at ?? 'now')),
                    'url_foto_upp'          => $fileSertifikat,
                    'url_dokumen_undangan'  => $fileSuratTugas,
                    'keterangan'            => $d->keterangan ?? 'Prestasi Mahasiswa Mandiri',
                    'mahasiswa'             => $arrayMahasiswa,
                    'dosen'                 => $arrayDosen,
                ];

                $isSimkatmawaSuccess = false;
                $isPdUnsriSuccess = false;
                $errorMessages = [];

                // =============================================================
                // TAHAP 1: EXECUTE PUSH TO SIMKATMAWA
                // =============================================================
                if ($d->simkatmawa == 0) {
                    Log::info("[NIM: {$nim}] [STEP 1/2] Memulai push ke SIMKATMAWA...");
                    try {
                        $reqSimkatmawa = new SimkatmawaUpload($recordSimkatmawa);
                        $resultSimkatmawa = $reqSimkatmawa->uploadPrestasiMahasiswa();

                        Log::info("[NIM: {$nim}] Response SIMKATMAWA:", [
                            'payload' => $recordSimkatmawa,
                            'result'  => $resultSimkatmawa
                        ]);

                        if (isset($resultSimkatmawa['error_code']) && $resultSimkatmawa['error_code'] == 0) {
                            $isSimkatmawaSuccess = true;
                            $d->simkatmawa = 1;
                            $d->id_simkatmawa = $resultSimkatmawa['data']['id_simkatmawa'] ?? $d->id_prestasi_mahasiswa;
                            $d->save();

                            $simkatmawaBerhasil++;
                            Log::info("[NIM: {$nim}] STEP 1 SUCCESS: SIMKATMAWA Berhasil | ID: {$d->id_simkatmawa}");
                        } else {
                            $simkatmawaGagal++;
                            $errorDesc = $resultSimkatmawa['error_desc'] ?? ($resultSimkatmawa['message'] ?? 'Gagal sync');
                            $errorMessages[] = "Simkatmawa: " . $errorDesc;
                            Log::warning("[NIM: {$nim}] STEP 1 FAILED: SIMKATMAWA Gagal ({$errorDesc}). Push ke PDUNSRI dibatalkan.");
                        }
                    } catch (\Exception $e) {
                        $simkatmawaGagal++;
                        $errorMessages[] = "Simkatmawa: " . $e->getMessage();
                        Log::error("[NIM: {$nim}] STEP 1 EXCEPTION: " . $e->getMessage());
                    }
                } else {
                    $isSimkatmawaSuccess = true;
                    Log::info("[NIM: {$nim}] [STEP 1/2] SIMKATMAWA Skipped (Sudah ter-sync sebelumnya).");
                }

                // =============================================================
                // TAHAP 2: EXECUTE PUSH TO PDUNSRI (JIKA SIMKATMAWA BERHASIL)
                // =============================================================
                if ($isSimkatmawaSuccess) {
                    if (($d->pdunsri ?? 0) == 0) {
                        Log::info("[NIM: {$nim}] [STEP 2/2] Memulai push ke PDUNSRI...");
                        try {
                            $resultPdUnsri = $pdUnsriController->uploadSinglePrestasi($d);

                            Log::info("[NIM: {$nim}] Response PDUNSRI:", [
                                'result' => $resultPdUnsri
                            ]);

                            if (isset($resultPdUnsri['error_code']) && $resultPdUnsri['error_code'] == 0) {
                                $isPdUnsriSuccess = true;
                                
                                // Cukup set flag pdunsri menjadi 1 tanpa update id_pdunsri
                                $d->pdunsri = 1;

                                $pdunsriBerhasil++;
                                Log::info("[NIM: {$nim}] STEP 2 SUCCESS: PDUNSRI Berhasil diproses.");
                            } else {
                                $pdunsriGagal++;
                                $errorDesc = $resultPdUnsri['error_desc'] ?? ($resultPdUnsri['message'] ?? 'Gagal sync');
                                $errorMessages[] = "PDUNSRI: " . $errorDesc;
                                Log::warning("[NIM: {$nim}] STEP 2 FAILED: PDUNSRI Gagal ({$errorDesc}).");
                            }
                        } catch (\Exception $e) {
                            $pdunsriGagal++;
                            $errorMessages[] = "PDUNSRI: " . $e->getMessage();
                            Log::error("[NIM: {$nim}] STEP 2 EXCEPTION: " . $e->getMessage());
                        }
                    } else {
                        $isPdUnsriSuccess = true;
                        Log::info("[NIM: {$nim}] [STEP 2/2] PDUNSRI Skipped (Sudah ter-sync sebelumnya).");
                    }
                }

                // =============================================================
                // UPDATE STATUS TEKS SYNC PADA DATABASE RECORD
                // =============================================================
                if ($isSimkatmawaSuccess && $isPdUnsriSuccess) {
                    $d->status_sync = 'Sudah sync (SIMKATMAWA & PDUNSRI)';
                } else {
                    $d->status_sync = !empty($errorMessages) ? implode(' | ', $errorMessages) : 'Gagal Sync';
                }
                $d->save();

                $progress = round((($index + 1) / $totalData) * 100, 2);

                // Kirim Payload JSON SSE dengan Data Terpisah
                echo "data: " . json_encode([
                    'progress'           => $progress,
                    'simkatmawaBerhasil' => $simkatmawaBerhasil,
                    'simkatmawaGagal'    => $simkatmawaGagal,
                    'pdunsriBerhasil'    => $pdunsriBerhasil,
                    'pdunsriGagal'       => $pdunsriGagal,
                ]) . "\n\n";

                ob_flush();
                flush();
            }

            Log::info("==================================================");
            Log::info("FINISH STREAMING PUSH PRESTASI MANDIRI");
            Log::info("SIMKATMAWA -> Berhasil: {$simkatmawaBerhasil} | Gagal: {$simkatmawaGagal}");
            Log::info("PDUNSRI    -> Berhasil: {$pdunsriBerhasil} | Gagal: {$pdunsriGagal}");
            Log::info("==================================================");
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }
}