<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
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
    //PREVIEW DATA REG
    public function previewReg()
    {
        $data = DB::connection('reg_con')
            ->table('mahasiswa')
            // ->limit(20)
            ->get();

        return view('universitas.import-data.registrasi.preview', [
            'data' => $data,
            'source' => 'Registrasi'
        ]);
    }

    // IMPORT DATA REG
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
    | DATA PEMBAYARAN
    |--------------------------------------------------------------------------
    */
    //PREVIEW DATA PEMBAYARAN
    public function previewPembayaran()
    {
        $data = DB::connection('keu_con')
            ->table('pembayaran')
            ->limit(20)
            ->get();

        return view('universitas.import-data.pembayaran', [
            'data' => $data,
            'source' => 'Pembayaran'
        ]);
    }

    // IMPORT DATA PEMBAYARAN
    public function importPembayaran()
    {
        // $min = DB::connection('keu_con')->table('pembayaran')->min('id_record_pembayaran');
        // $max = DB::connection('keu_con')->table('pembayaran')->max('id_record_pembayaran');

        $min = (int) DB::connection('keu_con')->table('tagihan')->min('id_record_tagihan');
        $max = (int) DB::connection('keu_con')->table('tagihan')->max('id_record_tagihan');

        $step = 50000;

        $batch = Bus::batch([])->name('Import Pembayaran')->dispatch();

        for ($i = $min; $i <= $max; $i += $step) {
            $batch->add(new PembayaranJob($i, $i + $step));
        }

        return back()->with('success', 'Import Pembayaran berjalan di background');
    }



    /*
    |--------------------------------------------------------------------------
    | DATA TAGIHAN
    |--------------------------------------------------------------------------
    */
    //PREVIEW DATA TAGIHAN
    public function previewTagihan()
    {
        $data = DB::connection('keu_con')
            ->table('tagihan')
            ->limit(20)
            ->get();

        return view('universitas.import-data.tagihan', [
            'data' => $data,
            'source' => 'Tagihan'
        ]);
    }

    // IMPORT DATA TAGIHAN
    public function importTagihan()
    {
        // $min = DB::connection('keu_con')->table('tagihan')->min('id_record_tagihan');
        // $max = DB::connection('keu_con')->table('tagihan')->max('id_record_tagihan');

        $min = (int) DB::connection('keu_con')->table('tagihan')->min('id_record_tagihan');
        $max = (int) DB::connection('keu_con')->table('tagihan')->max('id_record_tagihan');
        

        $step = 50000;

        $batch = Bus::batch([])->name('Import Tagihan')->dispatch();

        // for ($i = $min; $i <= $max; $i += $step) {
        for ($i = $min; $i <= $max; $i += $step) {
            $batch->add(new TagihanJob($i, $i + $step));
        }

        return back()->with('success', 'Import Tagihan berjalan di background');
    }

}
