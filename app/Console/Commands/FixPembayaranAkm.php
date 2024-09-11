<?php

namespace App\Console\Commands;

use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Registrasi;
use App\Models\Connection\Tagihan;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\SemesterAktif;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FixPembayaranAkm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-pembayaran-akm';

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
        $semester_aktif = SemesterAktif::first();

        $aktivitas_kuliah = AktivitasKuliahMahasiswa::where('feeder', 0)
                            ->where('id_semester', $semester_aktif->id_semester)->get();

        foreach ($aktivitas_kuliah as $akm) {
            $req = $this->checkPembayaran($akm->id_registrasi_mahasiswa, $semester_aktif->id_semester);

            $this->info($req['status']. ' ');
        }

    }

    private function checkPembayaran($id_reg, $semester_aktif)
    {
        $nim = RiwayatPendidikan::with('pembimbing_akademik')
                ->select('riwayat_pendidikans.*')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->first();

        $id_test = Registrasi::where('rm_nim', $nim->nim)->pluck('rm_no_test')->first();


        // dd($id_test);
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->first();
        // dd($beasiswa);
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->first();

        if($beasiswa){

            $akm->update([
                'id_pembiayaan' => 2,
            ]);

            return [
                'status' => 'success',
                'message' => 'Beasiswa',
                'data' => $nim->nim.' - '.$nim->nama_mahasiswa
            ];
        }

        $tagihan = Tagihan::with('pembayaran')
                    ->whereIn('tagihan.nomor_pembayaran', [$id_test, $nim])
                    ->where('tagihan.kode_periode', $semester_aktif)
                    ->first();

        if ($tagihan) {

        }

    }
}
