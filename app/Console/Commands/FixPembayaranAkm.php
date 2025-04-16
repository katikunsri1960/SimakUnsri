<?php

namespace App\Console\Commands;

use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Registrasi;
use App\Models\Connection\Tagihan;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\PembayaranManualMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\SemesterAktif;
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
        ini_set('memory_limit', '2048M');

        $semester_aktif = SemesterAktif::first();

        $aktivitas_kuliah = AktivitasKuliahMahasiswa::where('feeder', 0)
            ->where('id_semester', $semester_aktif->id_semester)->whereNull('id_pembiayaan')->get();

        foreach ($aktivitas_kuliah as $akm) {
            $req = $this->checkPembayaran($akm->id_registrasi_mahasiswa, $semester_aktif->id_semester);

            $this->info($req['status'].' - '.$req['message'].' - '.$req['data']);
        }

    }

    private function checkPembayaran($id_reg, $semester_aktif)
    {
        $nim = RiwayatPendidikan::with('pembimbing_akademik')
            ->select('riwayat_pendidikans.*')
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->first();

        // dd($id_test);
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->first();
        // dd($beasiswa);
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->where('id_semester', $semester_aktif)->where('feeder', 0)->first();

        if ($beasiswa) {

            $akm->update([
                'id_pembiayaan' => 3,
                'biaya_kuliah_smt' => 0,
            ]);

            return [
                'status' => 'success',
                'message' => 'Beasiswa',
                'data' => $nim->nim.' - '.$nim->nama_mahasiswa,
            ];
        }

        $manual_pembayaran = PembayaranManualMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->where('id_semester', $semester_aktif)->first();

        if ($manual_pembayaran) {
            $akm->update([
                'id_pembiayaan' => 1,
                'biaya_kuliah_smt' => $manual_pembayaran->nominal_ukt,
            ]);

            return [
                'status' => 'success',
                'message' => 'Manual Pembayaran',
                'data' => $nim->nim.' - '.$nim->nama_mahasiswa,
            ];
        }

        $id_test = Registrasi::where('rm_nim', $nim->nim)->pluck('rm_no_test')->first();

        $tagihan = Tagihan::with('pembayaran')
            ->whereIn('tagihan.nomor_pembayaran', [$id_test, $nim->nim])
            ->where('tagihan.kode_periode', $semester_aktif)
            ->first();

        if ($tagihan) {
            $akm->update([
                'id_pembiayaan' => 1,
                'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
            ]);

            return [
                'status' => 'success',
                'message' => 'Tagihan',
                'data' => $nim->nim.' - '.$nim->nama_mahasiswa,
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Tidak ada pembayaran',
            'data' => $nim->nim.' - '.$nim->nama_mahasiswa,
        ];

    }
}
