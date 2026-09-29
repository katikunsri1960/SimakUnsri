<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use App\Services\PdUnsri\PdUnsriAPI;
use App\Jobs\Import\TagihanJob;
use App\Jobs\Import\PembayaranJob;

class ImportExternalController extends Controller
{
    // Halaman utama
    public function index()
    {
        return view('universitas.import.index');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA REGISTRASI
    |--------------------------------------------------------------------------
    */
    public function previewReg()
    {
        $data = DB::connection('reg_con')
            ->table('mahasiswa')
            ->get();

        return view('universitas.import-data.registrasi.preview', [
            'data' => $data,
            'source' => 'Registrasi'
        ]);
    }

    public function importReg()
    {
        DB::connection('reg_con')
            ->table('mahasiswa')
            ->orderBy('nim')
            ->chunk(500, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('mahasiswa_simak')->updateOrInsert(
                        ['nim' => $row->nim],
                        [
                            'nama' => $row->nama,
                            'angkatan' => $row->angkatan,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            });

        return back()->with('success', 'Import data REG berhasil');
    }

    /*
    |--------------------------------------------------------------------------
    | DATA PEMBAYARAN (VIA PD UNSRI API)
    |--------------------------------------------------------------------------
    */

    public function previewPembayaran(PdUnsriAPI $api)
    {
        $probe = $api->getImportPembayaran(20, 0);

        // Ambil array 'data' dari response paginasi API
        $rows = $probe['data'] ?? [];

        return view('universitas.import-data.pembayaran', [
            'data' => $rows,
            'source' => 'Pembayaran (PD Unsri)'
        ]);
    }

    public function importPembayaran(PdUnsriAPI $api)
    {
        $probe = $api->getImportPembayaran(1, 0);

        if (!$probe || !isset($probe['totalData'])) {
            return redirect()->back()->with('error', 'Gagal mengambil data Pembayaran dari API PD Unsri.');
        }

        $count = $probe['totalData'];
        $limit = 1000;

        $batch = Bus::batch([])
            ->name('import-pembayaran-pdunsri')
            ->dispatch();

        for ($i = 0; $i < $count; $i += $limit) {
            $batch->add(new PembayaranJob($limit, $i));
        }

        return redirect()->back()->with('success', 'Sync Pembayaran Dimulai! Total data: ' . $count);
    }

    /*
    |--------------------------------------------------------------------------
    | DATA TAGIHAN (VIA PD UNSRI API)
    |--------------------------------------------------------------------------
    */
    public function previewTagihan(PdUnsriAPI $api)
    {
        $probe = $api->getImportTagihan(20, 0);

        // Ambil array 'data' dari response paginasi API
        $rows = $probe['data'] ?? [];

        return view('universitas.import-data.tagihan', [
            'data' => $rows,
            'source' => 'Tagihan (PD Unsri)'
        ]);
    }

    public function importTagihan(PdUnsriAPI $api)
    {
        try {
            // Ambil data tagihan dari API (misal 500-1000 record per batch)
            $response = $api->getImportTagihan(1000, 0);

            if (!$response || empty($response['data'])) {
                return redirect()->back()->with('error', 'Gagal mengambil data dari API atau data tagihan kosong.');
            }

            // Ambil array 'data' dari response paginasi API
            $dataTagihan = $response['data'];
            
            $importedCount = 0;

            DB::beginTransaction();

            foreach ($dataTagihan as $item) {
                // Mapping/Upsert data tagihan ke Database SIMAK
                // Sesuaikan nama kolom database SIMAK dengan key dari API
                DB::table('import_tagihan')->updateOrInsert(
                    [
                        // Key unik untuk identifikasi record (sesuaikan key dari API)
                        'id_record_tagihan' => $item['id_record_tagihan'] ?? $item['id'] ?? null,
                    ],
                    [
                        'nomor_pembayaran' => $item['nomor_pembayaran'] ?? null,
                        'nama'             => $item['nama'] ?? null,
                        'kode_fakultas'    => $item['kode_fakultas'] ?? null,
                        'nama_fakultas'    => $item['nama_fakultas'] ?? null,
                        'kode_prodi'       => $item['kode_prodi'] ?? null,
                        'nama_prodi'       => $item['nama_prodi'] ?? null,
                        'kode_periode'     => $item['kode_periode'] ?? null,
                        'updated_at'       => now(),
                    ]
                );

                $importedCount++;
            }

            DB::commit();

            return redirect()->back()->with('success', "Berhasil mengimpor {$importedCount} data tagihan.");

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Gagal Import Tagihan: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }
}