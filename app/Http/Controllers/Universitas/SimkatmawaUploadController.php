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
use Illuminate\Support\Facades\DB;

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
            ->Where(function ($q) {
                $q->where('simkatmawa', 0)
                  ->orWhere('pdunsri', 0);
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

    // Subquery mengambil 1 perwakilan id_prestasi per id_aktivitas untuk Simkatmawa
    $subQuery = PrestasiMahasiswa::select(DB::raw('MIN(id_prestasi) as min_id_prestasi'))
        ->where('penyelenggara', '!=', 'Belmawa')
        ->where('approved', 3)
        ->where(function ($q) {
            $q->where('simkatmawa', 0)
              ->orWhere('pdunsri', 0);
        })
        ->groupBy('id_aktivitas');

    // Load relasi ke Aktivitas Mahasiswa, Anggota Aktivitas, dan Bimbingan Dosen
    $query = PrestasiMahasiswa::with([
            'riwayat_pendidikan',
            'aktivitas_mahasiswa.anggota_aktivitas',
            'aktivitas_mahasiswa.bimbing_mahasiswa'
        ])
        ->whereIn('id_prestasi', $subQuery);

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

    Log::info("PUSH PRESTASI MANDIRI: Ditemukan {$totalData} data unik aktivitas yang akan diproses.");

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
            $idPrestasi = $d->id_prestasi;
            $aktivitas = $d->aktivitas_mahasiswa;

            Log::info("--- Processing Item [" . ($index + 1) . "/{$totalData}] ID Aktivitas: {$d->id_aktivitas} | Ketua/Representatif NIM: {$nim} ---");

            // 1. HELPER MAPPING ENUM SIMKATMAWA
            $rawLevel = $d->nama_tingkat_prestasi;
            $idTingkat = (string) ($d->id_tingkat_prestasi ?? '');

            if ($idTingkat === '6' || str_contains(strtolower($rawLevel), 'internasional')) {
                $level = 'INT';
            } elseif ($idTingkat === '5' || str_contains(strtolower($rawLevel), 'nasional')) {
                $level = 'NAS';
            } elseif ($idTingkat === '4' || str_contains(strtolower($rawLevel), 'propinsi')) {
                $level = 'PROV';
            } elseif ($idTingkat === '3' || str_contains(strtolower($rawLevel), 'kab/kota')) {
                $level = 'KAB';
            } else {
                $level = 'NAS';
            }

            // Mapping Kategori Simkatmawa
            $rawKategori = $d->kategori_simkatmawa;
            if (str_contains($rawKategori, '1')) {
                $kategori = 'RISNOV';
            } elseif (str_contains($rawKategori, 'SSH')) {
                $kategori = 'RISNOVSSH';
            } elseif (str_contains($rawKategori, '2')) {
                $kategori = 'SENBUD';
            } elseif (str_contains($rawKategori, '3')) {
                $kategori = 'OLAHRAGA';
            } else {
                $kategori = 'MINAT';
            }

            // Mapping Peringkat/Juara
            $rawPeringkat = $d->peringkat;
            if (str_contains($rawPeringkat, '1')) {
                $peringkat = 'JUARA1';
            } elseif (str_contains($rawPeringkat, '2')) {
                $peringkat = 'JUARA2';
            } elseif (str_contains($rawPeringkat, '3')) {
                $peringkat = 'JUARA3';
            } elseif (str_contains($rawPeringkat, '4')) {
                $peringkat = 'HARAPAN1';
            } elseif (str_contains($rawPeringkat, '5')) {
                $peringkat = 'HARAPAN2';
            } elseif (str_contains($rawPeringkat, '6')) {
                $peringkat = 'HARAPAN3';
            } elseif (str_contains($rawPeringkat, '9')) {
                $peringkat = 'APRESIASI';
            } else {
                $peringkat = 'PESERTA';
            }

            // Mapping Kelompok Prestasi (INDIVIDU / KELOMPOK)
            $rawKelompok = strtoupper(optional($aktivitas)->nama_jenis_anggota ?? optional($aktivitas)->jenis_anggota ?? '');
            $kelompokPrestasi = (str_contains($rawKelompok, 'PERSONAL') || $rawKelompok === '0') 
                ? 'INDIVIDU' 
                : 'KELOMPOK';

            // 2. PEMBENTUKAN URL FILE & ASSETS VALID TANPA LTRIM (AMAIN TERHINDAR DARI DOUBLE SLASH MAUPUN KEHILANGAN SLASH)
            $baseUrlStorage = 'https://akademik.unsri.ac.id/storage/';

            $urlSertifikat = $d->file_prestasi 
                ? $baseUrlStorage . $d->file_prestasi 
                : $baseUrlStorage . 'default-sertifikat.pdf';

            $urlFotoUpp = $d->file_foto_upp 
                ? $baseUrlStorage . $d->file_foto_upp 
                : $urlSertifikat;

            $urlUndangan = $d->file_undangan 
                ? $baseUrlStorage . $d->file_undangan 
                : $urlSertifikat;

            $urlKompetisi = $d->url_kompetisi ?? 'https://unsri.ac.id';

            // Clean up jika terjadi double slash pada URL file
            $urlSertifikat = str_replace('https://akademik.unsri.ac.id/storage//', 'https://akademik.unsri.ac.id/storage/', $urlSertifikat);
            $urlFotoUpp    = str_replace('https://akademik.unsri.ac.id/storage//', 'https://akademik.unsri.ac.id/storage/', $urlFotoUpp);
            $urlUndangan   = str_replace('https://akademik.unsri.ac.id/storage//', 'https://akademik.unsri.ac.id/storage/', $urlUndangan);

            // 3. SUSUN ARRAY MAHASISWA (Seluruh Anggota Aktivitas)
            $arrayMahasiswa = [];
            $anggotaAktivitas = optional($aktivitas)->anggota_aktivitas;

            if ($anggotaAktivitas && $anggotaAktivitas->count() > 0) {
                foreach ($anggotaAktivitas as $mhs) {
                    $arrayMahasiswa[] = [
                        'nim'  => $mhs->nim,
                        'nama' => strtoupper($mhs->nama_mahasiswa ?? '')
                    ];
                }
            } else {
                $arrayMahasiswa[] = [
                    'nim'  => $nim,
                    'nama' => strtoupper($d->nama_mahasiswa ?? '')
                ];
            }

            // 4. SUSUN ARRAY DOSEN PEMBIMBING
            $arrayDosen = [];
            $bimbinganDosen = optional($aktivitas)->bimbing_mahasiswa;

            if ($bimbinganDosen && $bimbinganDosen->count() > 0) {
                foreach ($bimbinganDosen as $dos) {
                    if ($dos->file_surat_tugas) {
                        $urlSuratTugas = $baseUrlStorage . $dos->file_surat_tugas;
                    } elseif (optional($aktivitas)->sk_tugas) {
                        $urlSuratTugas = $baseUrlStorage . $aktivitas->sk_tugas;
                    } else {
                        $urlSuratTugas = $urlSertifikat;
                    }

                    $urlSuratTugas = str_replace('https://akademik.unsri.ac.id/storage//', 'https://akademik.unsri.ac.id/storage/', $urlSuratTugas);

                    $arrayDosen[] = [
                        'nuptk'            => (string) ($dos->nidn ?? $dos->nuptk ?? $dos->id_dosen),
                        'nama'             => $dos->nama_dosen ?? '',
                        'url_surat_tugas'  => $urlSuratTugas
                    ];
                }
            }

            // 5. PAYLOAD SIMKATMAWA (1 Laporan Tunggal Per Aktivitas)
            $recordSimkatmawa = [
                'level'                 => $level,
                'kategori'              => $kategori,
                'lomba'                 => $d->nama_prestasi,
                'cabang'                => $d->nama_prestasi,
                'penyelenggara'         => $d->penyelenggara,
                'peringkat'             => $peringkat,
                'jumlah_unit_peserta'   => $d->jumlah_pt_peserta,
                'kelompok_prestasi'     => $kelompokPrestasi,
                'bentuk'                => $d->bentuk,
                'url_peserta'           => $urlKompetisi,
                'url_sertifikat'        => $urlSertifikat,
                'tgl_sertifikat'        => date('Y-m-d', strtotime($aktivitas->tanggal_sk_tugas)),
                'url_foto_upp'          => $urlFotoUpp,
                'url_dokumen_undangan'  => $urlUndangan,
                'keterangan'            => optional($aktivitas)->keterangan ?? 'Prestasi Mahasiswa',
                'mahasiswa'             => $arrayMahasiswa,
                'dosen'                 => $arrayDosen,
            ];

            $isSimkatmawaSuccess = false;
            $isPdUnsriSuccess = false;
            $errorMessages = [];

            // =============================================================
            // TAHAP 1: EXECUTE PUSH TO SIMKATMAWA (1 X PER AKTIVITAS)
            // =============================================================
            if ($d->simkatmawa == 0) {
                Log::info("[Aktivitas: {$d->id_aktivitas}] [STEP 1/2] Memulai push ke SIMKATMAWA...");
                try {
                    $reqSimkatmawa = new SimkatmawaUpload($recordSimkatmawa);
                    $resultSimkatmawa = $reqSimkatmawa->uploadPrestasiMahasiswa();

                    Log::info("[Aktivitas: {$d->id_aktivitas}] Response SIMKATMAWA:", [
                        'payload' => $recordSimkatmawa,
                        'result'  => $resultSimkatmawa
                    ]);

                    if (isset($resultSimkatmawa['error_code']) && $resultSimkatmawa['error_code'] == 0) {
                        $isSimkatmawaSuccess = true;
                        
                        // Set simkatmawa = 1 untuk seluruh baris prestasi di aktivitas ini
                        PrestasiMahasiswa::where('id_aktivitas', $d->id_aktivitas)->update([
                            'simkatmawa'    => 1,
                            'id_simkatmawa' => $resultSimkatmawa['data']['id_simkatmawa'] ?? $d->id_prestasi
                        ]);

                        $simkatmawaBerhasil++;
                        Log::info("[Aktivitas: {$d->id_aktivitas}] STEP 1 SUCCESS: SIMKATMAWA Berhasil.");
                    } else {
                        $simkatmawaGagal++;
                        $errorDesc = $resultSimkatmawa['error_desc'] ?? ($resultSimkatmawa['message'] ?? 'Gagal sync');
                        $errorMessages[] = "Simkatmawa: " . $errorDesc;
                        Log::warning("[Aktivitas: {$d->id_aktivitas}] STEP 1 FAILED: SIMKATMAWA Gagal ({$errorDesc}).");
                    }
                } catch (\Exception $e) {
                    $simkatmawaGagal++;
                    $errorMessages[] = "Simkatmawa: " . $e->getMessage();
                    Log::error("[Aktivitas: {$d->id_aktivitas}] STEP 1 EXCEPTION: " . $e->getMessage());
                }
            } else {
                $isSimkatmawaSuccess = true;
                Log::info("[Aktivitas: {$d->id_aktivitas}] [STEP 1/2] SIMKATMAWA Skipped.");
            }

            // =============================================================
            // TAHAP 2: EXECUTE PUSH TO PDUNSRI (DILAPORKAN PER MAHASISWA)
            // =============================================================
            if ($isSimkatmawaSuccess) {
                // Ambil semua record PrestasiMahasiswa milik aktivitas ini
                $semuaPrestasiAktivitas = PrestasiMahasiswa::where('id_aktivitas', $d->id_aktivitas)->get();
                $allPdUnsriOk = true;

                foreach ($semuaPrestasiAktivitas as $itemPrestasi) {
                    if (($itemPrestasi->pdunsri ?? 0) == 0) {
                        Log::info("[ID Prestasi: {$itemPrestasi->id_prestasi}] [STEP 2/2] Memulai push ke PDUNSRI per mahasiswa...");
                        try {
                            $resultPdUnsri = $pdUnsriController->uploadSinglePrestasi($itemPrestasi);

                            if (isset($resultPdUnsri['error_code']) && $resultPdUnsri['error_code'] == 0) {
                                $itemPrestasi->pdunsri = 1;
                                $itemPrestasi->save();

                                $pdunsriBerhasil++;
                                Log::info("[ID Prestasi: {$itemPrestasi->id_prestasi}] STEP 2 SUCCESS: PDUNSRI Berhasil.");
                            } else {
                                $allPdUnsriOk = false;
                                $pdunsriGagal++;
                                $errorDesc = $resultPdUnsri['error_desc'] ?? ($resultPdUnsri['message'] ?? 'Gagal sync');
                                $errorMessages[] = "PDUNSRI (Mhs {$itemPrestasi->nama_mahasiswa}): " . $errorDesc;
                                Log::warning("[ID Prestasi: {$itemPrestasi->id_prestasi}] STEP 2 FAILED: PDUNSRI Gagal ({$errorDesc}).");
                            }
                        } catch (\Exception $e) {
                            $allPdUnsriOk = false;
                            $pdunsriGagal++;
                            $errorMessages[] = "PDUNSRI (Mhs {$itemPrestasi->nama_mahasiswa}): " . $e->getMessage();
                            Log::error("[ID Prestasi: {$itemPrestasi->id_prestasi}] STEP 2 EXCEPTION: " . $e->getMessage());
                        }
                    }
                }
                $isPdUnsriSuccess = $allPdUnsriOk;
            }

            // =============================================================
            // UPDATE STATUS TEKS SYNC UNTUK SEMUA REKOR DALAM AKTIVITAS
            // =============================================================
            $statusText = ($isSimkatmawaSuccess && $isPdUnsriSuccess)
                ? 'Sudah sync (SIMKATMAWA & PDUNSRI)'
                : (!empty($errorMessages) ? implode(' | ', $errorMessages) : 'Gagal Sync');

            PrestasiMahasiswa::where('id_aktivitas', $d->id_aktivitas)->update([
                'status_sync' => $statusText
            ]);

            $progress = round((($index + 1) / $totalData) * 100, 2);

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