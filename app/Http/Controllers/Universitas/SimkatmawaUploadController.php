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
use App\Services\Feeder\FeederUpload;
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

        return view('universitas.feeder-upload.mahasiswa.prestasi-mandiri', [
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
                $q->where('feeder', 0)
                  ->orWhere('simkatmawa', 0)
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
    Log::info("START STREAMING PUSH PRESTASI MANDIRI (FEEDER DIKTI -> SIMKATMAWA -> PDUNSRI)");
    Log::info("==================================================", [
        'id_prodi_filter' => $request->input('id_prodi', 'all')
    ]);

    // Subquery mengambil 1 perwakilan id_prestasi per id_aktivitas
    $subQuery = PrestasiMahasiswa::select(DB::raw('MIN(id_prestasi) as min_id_prestasi'))
        ->where('penyelenggara', '!=', 'Belmawa')
        ->where('approved', 3)
        ->where(function ($q) {
            $q->where('feeder', 0)
              ->orWhere('simkatmawa', 0)
              ->orWhere('pdunsri', 0);
        })
        ->groupBy('id_aktivitas');

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

    $feederBerhasil     = 0;
    $feederGagal        = 0;
    $simkatmawaBerhasil = 0;
    $simkatmawaGagal    = 0;
    $pdunsriBerhasil    = 0;
    $pdunsriGagal       = 0;

    $pdUnsriController = new PdUnsriUploadController();

    $response = new StreamedResponse(function () use (
        $data, 
        $totalData, 
        &$feederBerhasil,
        &$feederGagal,
        &$simkatmawaBerhasil, 
        &$simkatmawaGagal, 
        &$pdunsriBerhasil, 
        &$pdunsriGagal, 
        $pdUnsriController
    ) {
        set_time_limit(0);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        foreach ($data as $index => $d) {

            $nim = optional($d->riwayat_pendidikan)->nim ?? 'UNKNOWN';
            $aktivitas = $d->aktivitas_mahasiswa;

            Log::info("--- Processing Item [" . ($index + 1) . "/{$totalData}] ID Aktivitas: {$d->id_aktivitas} | Ketua/Representatif NIM: {$nim} ---");

            $isFeederSuccess     = false;
            $isSimkatmawaSuccess = false;
            $isPdUnsriSuccess    = false;
            $errorMessages       = [];

            // =============================================================
            // TAHAP 1: EXECUTE PUSH TO FEEDER DIKTI
            // =============================================================
            $semuaPrestasiAktivitas = PrestasiMahasiswa::with('riwayat_pendidikan')
                ->where('id_aktivitas', $d->id_aktivitas)
                ->get();

            $allFeederOk = true;

            foreach ($semuaPrestasiAktivitas as $itemPrestasi) {
                if (($itemPrestasi->feeder ?? 0) == 0) {
                    Log::info("[ID Prestasi Lokal: {$itemPrestasi->id_prestasi}] [STEP 1/3] Memulai push ke FEEDER DIKTI...");
                    
                    try {
                        $recordFeeder = [
                            'id_mahasiswa'        => $itemPrestasi->id_mahasiswa,
                            'id_jenis_prestasi'   => (int) $itemPrestasi->id_jenis_prestasi,
                            'id_tingkat_prestasi' => (int) $itemPrestasi->id_tingkat_prestasi,
                            'nama_prestasi'       => $itemPrestasi->nama_prestasi,
                            'tahun_prestasi'      => (int) ($itemPrestasi->tahun_prestasi ?? $itemPrestasi->tahun),
                            'penyelenggara'       => $itemPrestasi->penyelenggara,
                            'peringkat'           => $itemPrestasi->peringkat ? (int) $itemPrestasi->peringkat : 9,
                            'id_aktivitas'        => $d->id_aktivitas,
                        ];

                        $act = 'InsertPrestasiMahasiswa';
                        $actGet = 'GetPrestasiMahasiswa';
                        $recordGet = "id_mahasiswa = '{$itemPrestasi->id_mahasiswa}' AND nama_prestasi = '" . addslashes($itemPrestasi->nama_prestasi) . "'";

                        $feederReq = new FeederUpload($act, $recordFeeder, $actGet, $recordGet);
                        $resultFeeder = $feederReq->uploadPrestasiMahasiswa();

                        Log::info("[ID Prestasi Lokal: {$itemPrestasi->id_prestasi}] RESPONSE FEEDER RAW:", [
                            'response' => $resultFeeder
                        ]);

                        $errorCode = $resultFeeder['error_code'] ?? ($resultFeeder['response']['error_code'] ?? null);

                        if ($errorCode === 0) {
                            $uuidFeederBaru = $resultFeeder['data']['id_prestasi'] 
                                ?? ($resultFeeder['response']['data']['id_prestasi'] 
                                ?? ($resultFeeder['id_prestasi'] ?? null));

                            DB::beginTransaction();

                            $updatePayload = [
                                'feeder' => 1
                            ];

                            if ($uuidFeederBaru) {
                                // Update id_prestasi lokal dengan UUID Feeder resmi
                                $updatePayload['id_prestasi'] = $uuidFeederBaru;
                            }

                            PrestasiMahasiswa::where('id', $itemPrestasi->id)->update($updatePayload);

                            DB::commit();

                            $feederBerhasil++;
                            Log::info("[ID Prestasi: {$itemPrestasi->id_prestasi}] STEP 1 SUCCESS: Feeder Berhasil & ID Prestasi di-update: {$uuidFeederBaru}");
                        } else {
                            $allFeederOk = false;
                            $feederGagal++;
                            $errorDesc = $resultFeeder['error_desc'] ?? ($resultFeeder['response']['error_desc'] ?? ($resultFeeder['message'] ?? 'Gagal sync Feeder'));
                            $errorMessages[] = "Feeder: " . substr($errorDesc, 0, 150);
                            Log::warning("[ID Prestasi Lokal: {$itemPrestasi->id_prestasi}] STEP 1 FAILED: FEEDER DIKTI Gagal ({$errorDesc}).");
                        }
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        $allFeederOk = false;
                        $feederGagal++;
                        $errorMessages[] = "Feeder Err: " . substr($e->getMessage(), 0, 150);
                        Log::error("[ID Prestasi Lokal: {$itemPrestasi->id_prestasi}] STEP 1 EXCEPTION: " . $e->getMessage());
                    }
                }
            }
            $isFeederSuccess = $allFeederOk;

            // Refetch data terbaru agar id_prestasi resmi Feeder langsung terbaca di langkah berikutnya
            $d->refresh();

            // =============================================================
            // TAHAP 2: EXECUTE PUSH TO SIMKATMAWA
            // =============================================================
            if ($isFeederSuccess) {
                // HELPER MAPPING ENUM SIMKATMAWA
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

                $rawKelompok = strtoupper(optional($aktivitas)->nama_jenis_anggota ?? optional($aktivitas)->jenis_anggota ?? '');
                $kelompokPrestasi = (str_contains($rawKelompok, 'PERSONAL') || $rawKelompok === '0') 
                    ? 'INDIVIDU' 
                    : 'KELOMPOK';

                $urlSertifikat = $d->file_prestasi;
                $urlFotoUpp    = $d->file_foto_upp ?? $urlSertifikat;
                $urlUndangan   = $d->file_undangan ?? $urlSertifikat;
                $urlKompetisi  = $d->url_kompetisi ?? 'https://unsri.ac.id';

                $arrayMahasiswa = [];
                $anggotaAktivitas = optional($aktivitas)->anggota_aktivitas;

                if ($anggotaAktivitas && $anggotaAktivitas->count() > 0) {
                    foreach ($anggotaAktivitas as $mhs) {
                        $arrayMahasiswa[] = [
                            'nim'  => $mhs->nim,
                            'nama' => strtoupper($mhs->nama_mahasiswa)
                        ];
                    }
                } else {
                    $arrayMahasiswa[] = [
                        'nim'  => $nim,
                        'nama' => strtoupper($d->nama_mahasiswa)
                    ];
                }

                $arrayDosen = [];
                $bimbinganDosen = optional($aktivitas)->bimbing_mahasiswa;

                if ($bimbinganDosen && $bimbinganDosen->count() > 0) {
                    foreach ($bimbinganDosen as $dos) {
                        $urlSuratTugas = $dos->file_surat_tugas ?? ($d->sk_pembimbing ?? $urlSertifikat);

                        $arrayDosen[] = [
                            'nuptk'            => (string) ($dos->nidn ?? $dos->nuptk),
                            'nama'             => $dos->nama_dosen,
                            'url_surat_tugas'  => $urlSuratTugas
                        ];
                    }
                }

                $recordSimkatmawa = [
                    'level'                 => $level,
                    'kategori'              => $kategori,
                    'lomba'                 => $d->nama_prestasi,
                    'cabang'                => $d->nama_prestasi,
                    'penyelenggara'         => $d->penyelenggara,
                    'peringkat'             => $peringkat,
                    'jumlah_unit_peserta'   => (string) ($d->jumlah_unit_peserta ?? $d->jumlah_pt_peserta ?? '1'),
                    'kelompok_prestasi'     => $kelompokPrestasi,
                    'bentuk'                => strtoupper($d->bentuk ?? 'LURING'),
                    'url_peserta'           => $urlKompetisi,
                    'url_sertifikat'        => $urlSertifikat,
                    'tgl_sertifikat'        => date('Y-m-d', strtotime(optional($aktivitas)->tanggal_sk_tugas ?? $d->created_at)),
                    'url_foto_upp'          => $urlFotoUpp,
                    'url_dokumen_undangan'  => $urlUndangan,
                    'keterangan'            => optional($aktivitas)->keterangan ?? 'Prestasi Mahasiswa',
                    'mahasiswa'             => $arrayMahasiswa,
                    'dosen'                 => $arrayDosen,
                ];

                if ($d->simkatmawa == 0) {
                    Log::info("[Aktivitas: {$d->id_aktivitas}] [STEP 2/3] Memulai push ke SIMKATMAWA...");
                    try {
                        $reqSimkatmawa = new SimkatmawaUpload($recordSimkatmawa);
                        $resultSimkatmawa = $reqSimkatmawa->uploadPrestasiMahasiswa();

                        if (isset($resultSimkatmawa['error_code']) && $resultSimkatmawa['error_code'] == 0) {
                            $isSimkatmawaSuccess = true;
                            
                            PrestasiMahasiswa::where('id_aktivitas', $d->id_aktivitas)->update([
                                'simkatmawa'    => 1,
                                'id_simkatmawa' => $resultSimkatmawa['data']['id_simkatmawa'] ?? $d->id_prestasi
                            ]);

                            $simkatmawaBerhasil++;
                            Log::info("[Aktivitas: {$d->id_aktivitas}] STEP 2 SUCCESS: SIMKATMAWA Berhasil.");
                        } else {
                            $simkatmawaGagal++;
                            $errorDesc = $resultSimkatmawa['error_desc'] ?? ($resultSimkatmawa['message'] ?? 'Gagal sync');
                            $errorMessages[] = "Simkatmawa: " . substr($errorDesc, 0, 150);
                            Log::warning("[Aktivitas: {$d->id_aktivitas}] STEP 2 FAILED: SIMKATMAWA Gagal ({$errorDesc}).");
                        }
                    } catch (\Exception $e) {
                        $simkatmawaGagal++;
                        $errorMessages[] = "Simkatmawa: " . substr($e->getMessage(), 0, 150);
                        Log::error("[Aktivitas: {$d->id_aktivitas}] STEP 2 EXCEPTION: " . $e->getMessage());
                    }
                } else {
                    $isSimkatmawaSuccess = true;
                }
            }

            // =============================================================
            // TAHAP 3: EXECUTE PUSH TO PDUNSRI (MEMAKAI ID PRESTASI TERBARU)
            // =============================================================
            if ($isFeederSuccess && $isSimkatmawaSuccess) {
                $semuaPrestasiAktivitas = PrestasiMahasiswa::where('id_aktivitas', $d->id_aktivitas)->get();
                $allPdUnsriOk = true;

                foreach ($semuaPrestasiAktivitas as $itemPrestasi) {
                    if (($itemPrestasi->pdunsri ?? 0) == 0) {
                        Log::info("[ID Prestasi: {$itemPrestasi->id_prestasi}] [STEP 3/3] Memulai push ke PDUNSRI...");
                        try {
                            // uploadSinglePrestasi akan membaca itemPrestasi yang id_prestasi-nya sudah ter-update UUID Feeder
                            $resultPdUnsri = $pdUnsriController->uploadSinglePrestasi($itemPrestasi);

                            if (isset($resultPdUnsri['error_code']) && $resultPdUnsri['error_code'] == 0) {
                                $itemPrestasi->pdunsri = 1;
                                $itemPrestasi->save();

                                $pdunsriBerhasil++;
                                Log::info("[ID Prestasi: {$itemPrestasi->id_prestasi}] STEP 3 SUCCESS: PDUNSRI Berhasil.");
                            } else {
                                $allPdUnsriOk = false;
                                $pdunsriGagal++;
                                $errorDesc = $resultPdUnsri['error_desc'] ?? ($resultPdUnsri['message'] ?? 'Gagal sync');
                                $errorMessages[] = "PDUNSRI (Mhs {$itemPrestasi->nama_mahasiswa}): " . substr($errorDesc, 0, 150);
                                Log::warning("[ID Prestasi: {$itemPrestasi->id_prestasi}] STEP 3 FAILED: PDUNSRI Gagal ({$errorDesc}).");
                            }
                        } catch (\Exception $e) {
                            $allPdUnsriOk = false;
                            $pdunsriGagal++;
                            $errorMessages[] = "PDUNSRI: " . substr($e->getMessage(), 0, 150);
                            Log::error("[ID Prestasi: {$itemPrestasi->id_prestasi}] STEP 3 EXCEPTION: " . $e->getMessage());
                        }
                    }
                }
                $isPdUnsriSuccess = $allPdUnsriOk;
            }

            // =============================================================
            // UPDATE STATUS TEKS SYNC UNTUK SEMUA REKOR DALAM AKTIVITAS
            // =============================================================
            $statusText = ($isFeederSuccess && $isSimkatmawaSuccess && $isPdUnsriSuccess)
                ? 'Sudah sync (FEEDER, SIMKATMAWA & PDUNSRI)'
                : (!empty($errorMessages) ? substr(implode(' | ', $errorMessages), 0, 250) : 'Gagal Sync');

            PrestasiMahasiswa::where('id_aktivitas', $d->id_aktivitas)->update([
                'status_sync' => $statusText
            ]);

            $progress = round((($index + 1) / $totalData) * 100, 2);

            echo "data: " . json_encode([
                'progress'           => $progress,
                'feederBerhasil'     => $feederBerhasil,
                'feederGagal'        => $feederGagal,
                'simkatmawaBerhasil' => $simkatmawaBerhasil,
                'simkatmawaGagal'    => $simkatmawaGagal,
                'pdunsriBerhasil'    => $pdunsriBerhasil,
                'pdunsriGagal'       => $pdunsriGagal,
            ]) . "\n\n";

            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
        }

        Log::info("==================================================");
        Log::info("FINISH STREAMING PUSH PRESTASI MANDIRI");
        Log::info("FEEDER     -> Berhasil: {$feederBerhasil} | Gagal: {$feederGagal}");
        Log::info("SIMKATMAWA -> Berhasil: {$simkatmawaBerhasil} | Gagal: {$simkatmawaGagal}");
        Log::info("PDUNSRI    -> Berhasil: {$pdunsriBerhasil} | Gagal: {$pdunsriGagal}");
        Log::info("==================================================");
    });

    $response->headers->set('Content-Type', 'text/event-stream');
    $response->headers->set('Cache-Control', 'no-cache');
    $response->headers->set('Connection', 'keep-alive');
    $response->headers->set('X-Accel-Buffering', 'no');

    return $response;
}
}