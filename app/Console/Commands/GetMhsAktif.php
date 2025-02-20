<?php

namespace App\Console\Commands;

use App\Models\Connection\Registrasi;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Console\Command;

class GetMhsAktif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-mhs-aktif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = RiwayatPendidikan::leftJoin('program_studis as prodi', 'prodi.id_prodi', 'riwayat_pendidikans.id_prodi')
                ->leftJoin('fakultas as f', 'f.id', 'prodi.fakultas_id')
                ->whereNull('riwayat_pendidikans.id_jenis_keluar')
                ->select('f.nama_fakultas', 'prodi.kode_program_studi', 'prodi.nama_jenjang_pendidikan as jenjang', 'prodi.nama_program_studi as prodi', 'riwayat_pendidikans.nim', 'riwayat_pendidikans.nama_mahasiswa')
                ->get();
        
        $id_test = Registrasi::where('rm_nim',)->pluck('rm_no_test')->first();

        $this->info('Data mahasiswa aktif: ' . $data->count());
    }
}
