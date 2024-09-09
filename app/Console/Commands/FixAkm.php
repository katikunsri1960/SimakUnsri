<?php

namespace App\Console\Commands;

use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Registrasi;
use App\Models\Connection\Tagihan;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FixAkm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-akm';

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
        // ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new RiwayatPendidikan();

        $p = ProgramStudi::where('status', 'A')->get();

        $allData = AktivitasKuliahMahasiswa::where('id_semester', $semesterAktif)
            ->get()
            ->keyBy('id_registrasi_mahasiswa');

        $file = fopen('public/akm_no_transkrip.txt', 'a');

        foreach ($p as $prodi) {
            $id_prodi = $prodi->id_prodi;
            $data = $db->detail_isi_krs($id_prodi, $semesterAktif);

            foreach ($data as $item) {
                if (!isset($allData[$item->id_registrasi_mahasiswa])) {
                    $req = $this->approve($item->id_registrasi_mahasiswa);
                    if ($req['status'] == 'No Transkrip!') {
                        fwrite($file, $req['nim'] . ' - ' . $req['nama_mahasiswa'] . "\n");
                    }
                    $this->info($req['status'] . ' - ' . $req['nim'] . ' - ' . $req['nama_mahasiswa']);
                }
            }
        }

        fclose($file);

    }

    private function approve($id_reg)
    {
        $semester_aktif = SemesterAktif::first();
        $data = PesertaKelasKuliah::with(['kelas_kuliah', 'kelas_kuliah.matkul'])
                ->whereHas('kelas_kuliah', function($query) use ($semester_aktif) {
                    $query->where('id_semester', $semester_aktif->id_semester);
                })
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->orderBy('kode_mata_kuliah')
                ->get();

        $db = new MataKuliah();
        $db_akt = new AktivitasMahasiswa();

        $akm_aktif= AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                ->where('id_semester', $semester_aktif->id_semester)
                ->first();


                // dd($akm_aktif);

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
                ->first();

        if($akm_aktif){
            return [
                'status' => 'AKM sudah ada!',
                'nim' => $riwayat_pendidikan->nim,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            ];
        }

        $transkrip = TranskripMahasiswa::select(
                        DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                        DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
                    )
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->whereNotIn('nilai_huruf', ['F', ''])
                    ->groupBy('id_registrasi_mahasiswa')
                    ->first();

        if ((!$transkrip || $transkrip->ipk === null) && $riwayat_pendidikan->id_periode_masuk != $semester_aktif->id_semester) {
            return [
                'status' => 'No Transkrip!',
                'nim' => $riwayat_pendidikan->nim,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            ];

        }

        $data_mbkm = PesertaKelasKuliah::with(['kelas_kuliah', 'kelas_kuliah.matkul'])
                    ->whereHas('kelas_kuliah', function($query) use ($semester_aktif,$riwayat_pendidikan) {
                        $query->where('id_semester', $semester_aktif->id_semester)->whereNot('id_prodi', $riwayat_pendidikan->id_prodi);
                    })
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->orderBy('kode_mata_kuliah')
                    ->count();

        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->first();

        $id_test = Registrasi::where('rm_nim', $riwayat_pendidikan->nim)->pluck('rm_no_test')->first();

        $tagihan = Tagihan::with('pembayaran')
                    ->whereIn('nomor_pembayaran', [$id_test, $riwayat_pendidikan->nim])
                    ->where('kode_periode', $semester_aktif->id_semester)
                    ->first();

        list($krs_akt, $data_akt_ids) = $db_akt->getKrsAkt($id_reg, $semester_aktif->id_semester);

        $sks_max = $db->getSksMax($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_periode_masuk);

        $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $semester_aktif->id_semester, $data_akt_ids);

        $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_prodi);

        $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
        $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
        $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

        $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt;



        $aktivitas = $db_akt->with('anggota_aktivitas_personal', 'konversi')
                    ->whereHas('anggota_aktivitas_personal', function($query) use ($id_reg) {
                        $query->where('id_registrasi_mahasiswa', $id_reg);
                    })
                    ->where('id_semester', $semester_aktif->id_semester)
                    ->get();


        try {

            DB::beginTransaction();

            if($akm_aktif){

                if($akm_aktif->feeder == '1'){
                    $result = [
                        'status' => 'error',
                        'message' => 'Data sudah di sinkronisasi ke feeder!',
                    ];

                    return $result;
                }

                foreach ($aktivitas as $item) {
                    $item->update([
                        'approve_krs' => '1'
                    ]);
                }

                foreach ($data as $item) {
                    $tgl_approve = '2024-09-06';
                    if ($item->tanggal_approve == null) {
                        $item->update([
                            // 'approved' => '1',
                            'tanggal_approve' => $tgl_approve
                        ]);
                    }

                }

                if($data_mbkm > 0){
                    if($beasiswa){
                        if($beasiswa->id_pembiayaan == '3'){
                            $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
                                'feeder' => 0,
                                'id_registrasi_mahasiswa' => $id_reg,
                                'nim' => $riwayat_pendidikan->nim,
                                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                                'id_prodi' => $riwayat_pendidikan->id_prodi,
                                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                                'id_semester'=> $semester_aktif->id_semester,
                                'nama_semester'=> $semester_aktif->semester->nama_semester,
                                'id_status_mahasiswa' => 'M',
                                'nama_status_mahasiswa' => 'Kampus Merdeka',
                                'ips'=> '0.00',
                                'ipk'=> $transkrip->ipk == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                                'sks_semester'=> $total_sks,
                                'sks_total'=> $transkrip->total_sks == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                                'biaya_kuliah_smt' => 0,
                                'id_pembiayaan' => 3,
                                'status_sync' => 'belum sync',
                            ]);
                        }else if($beasiswa->id_pembiayaan == '2' && $tagihan){
                            $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
                                'feeder' => 0,
                                'id_registrasi_mahasiswa' => $id_reg,
                                'nim' => $riwayat_pendidikan->nim,
                                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                                'id_prodi' => $riwayat_pendidikan->id_prodi,
                                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                                'id_semester'=> $semester_aktif->id_semester,
                                'nama_semester'=> $semester_aktif->semester->nama_semester,
                                'id_status_mahasiswa' => 'M',
                                'nama_status_mahasiswa' => 'Kampus Merdeka',
                                'ips'=> '0.00',
                                'ipk'=> $transkrip->ipk == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                                'sks_semester'=> $total_sks,
                                'sks_total'=> $transkrip->total_sks == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                                'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
                                'id_pembiayaan' => 2,
                                'status_sync' => 'belum sync',
                            ]);
                        }
                    }else if($tagihan){
                        $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
                            'feeder' => 0,
                            'id_registrasi_mahasiswa' => $id_reg,
                            'nim' => $riwayat_pendidikan->nim,
                            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                            'id_prodi' => $riwayat_pendidikan->id_prodi,
                            'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                            'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                            'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                            'id_semester'=> $semester_aktif->id_semester,
                            'nama_semester'=> $semester_aktif->semester->nama_semester,
                            'id_status_mahasiswa' => 'M',
                            'nama_status_mahasiswa' => 'Kampus Merdeka',
                            'ips'=> '0.00',
                            'ipk'=> $transkrip->ipk == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                            'sks_semester'=> $total_sks,
                            'sks_total'=> $transkrip->total_sks == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                            'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
                            'id_pembiayaan' => 1,
                            'status_sync' => 'belum sync',
                        ]);
                    }else{
                        $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
                            'feeder' => 0,
                            'id_registrasi_mahasiswa' => $id_reg,
                            'nim' => $riwayat_pendidikan->nim,
                            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                            'id_prodi' => $riwayat_pendidikan->id_prodi,
                            'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                            'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                            'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                            'id_semester'=> $semester_aktif->id_semester,
                            'nama_semester'=> $semester_aktif->semester->nama_semester,
                            'id_status_mahasiswa' => 'M',
                            'nama_status_mahasiswa' => 'Kampus Merdeka',
                            'ips'=> '0.00',
                            'ipk'=> $transkrip->ipk == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                            'sks_semester'=> $total_sks,
                            'sks_total'=> $transkrip->total_sks == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                            'biaya_kuliah_smt' => 0,
                            'id_pembiayaan' => NULL,
                            'status_sync' => 'belum sync',
                        ]);
                    }
                }else{
                    if($beasiswa){
                        if($beasiswa->id_pembiayaan == '3'){
                            $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
                                'feeder' => 0,
                                'id_registrasi_mahasiswa' => $id_reg,
                                'nim' => $riwayat_pendidikan->nim,
                                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                                'id_prodi' => $riwayat_pendidikan->id_prodi,
                                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                                'id_semester'=> $semester_aktif->id_semester,
                                'nama_semester'=> $semester_aktif->semester->nama_semester,
                                'id_status_mahasiswa' => 'A',
                                'nama_status_mahasiswa' => 'Aktif',
                                'ips'=> '0.00',
                                'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                                'sks_semester'=> $total_sks,
                                'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                                'biaya_kuliah_smt' => 0,
                                'id_pembiayaan' => 3,
                                'status_sync' => 'belum sync',
                            ]);
                        }else if($beasiswa->id_pembiayaan == '2' && $tagihan){
                            $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
                                'feeder' => 0,
                                'id_registrasi_mahasiswa' => $id_reg,
                                'nim' => $riwayat_pendidikan->nim,
                                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                                'id_prodi' => $riwayat_pendidikan->id_prodi,
                                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                                'id_semester'=> $semester_aktif->id_semester,
                                'nama_semester'=> $semester_aktif->semester->nama_semester,
                                'id_status_mahasiswa' => 'A',
                                'nama_status_mahasiswa' => 'Aktif',
                                'ips'=> '0.00',
                                'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                                'sks_semester'=> $total_sks,
                                'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                                'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
                                'id_pembiayaan' => 2,
                                'status_sync' => 'belum sync',
                            ]);
                        }
                    }else if($tagihan){
                        $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
                            'feeder' => 0,
                            'id_registrasi_mahasiswa' => $id_reg,
                            'nim' => $riwayat_pendidikan->nim,
                            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                            'id_prodi' => $riwayat_pendidikan->id_prodi,
                            'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                            'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                            'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                            'id_semester'=> $semester_aktif->id_semester,
                            'nama_semester'=> $semester_aktif->semester->nama_semester,
                            'id_status_mahasiswa' => 'A',
                            'nama_status_mahasiswa' => 'Aktif',
                            'ips'=> '0.00',
                            'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                            'sks_semester'=> $total_sks,
                            'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                            'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
                            'id_pembiayaan' => 1,
                            'status_sync' => 'belum sync',
                        ]);
                    }else{
                        $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
                            'feeder' => 0,
                            'id_registrasi_mahasiswa' => $id_reg,
                            'nim' => $riwayat_pendidikan->nim,
                            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                            'id_prodi' => $riwayat_pendidikan->id_prodi,
                            'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                            'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                            'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                            'id_semester'=> $semester_aktif->id_semester,
                            'nama_semester'=> $semester_aktif->semester->nama_semester,
                            'id_status_mahasiswa' => 'A',
                            'nama_status_mahasiswa' => 'Aktif',
                            'ips'=> '0.00',
                            'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                            'sks_semester'=> $total_sks,
                            'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                            'biaya_kuliah_smt' => 0,
                            'id_pembiayaan' => NULL,
                            'status_sync' => 'belum sync',
                        ]);
                    }
                }

            }else{

                foreach ($aktivitas as $item) {
                    $item->update([
                        'approve_krs' => '1'
                    ]);
                }

                foreach ($data as $item) {
                    $tgl_approve = '2024-09-06';
                    if ($item->tanggal_approve == null) {
                        $item->update([
                            // 'approved' => '1',
                            'tanggal_approve' => $tgl_approve
                        ]);
                    }
                }
                if($data_mbkm > 0){
                    if($beasiswa){
                        if($beasiswa->id_pembiayaan == '3'){
                            $peserta = AktivitasKuliahMahasiswa::create([
                                'feeder' => 0,
                                'id_registrasi_mahasiswa' => $id_reg,
                                'nim' => $riwayat_pendidikan->nim,
                                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                                'id_prodi' => $riwayat_pendidikan->id_prodi,
                                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                                'id_semester'=> $semester_aktif->id_semester,
                                'nama_semester'=> $semester_aktif->semester->nama_semester,
                                'id_status_mahasiswa' => 'M',
                                'nama_status_mahasiswa' => 'Kampus Merdeka',
                                'ips'=> '0.00',
                                'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                                'sks_semester'=> $total_sks,
                                'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                                'biaya_kuliah_smt' => 0,
                                'id_pembiayaan' => 3,
                                'status_sync' => 'belum sync',
                            ]);
                        }else if($beasiswa->id_pembiayaan == '2' && $tagihan){
                            $peserta = AktivitasKuliahMahasiswa::create([
                                'feeder' => 0,
                                'id_registrasi_mahasiswa' => $id_reg,
                                'nim' => $riwayat_pendidikan->nim,
                                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                                'id_prodi' => $riwayat_pendidikan->id_prodi,
                                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                                'id_semester'=> $semester_aktif->id_semester,
                                'nama_semester'=> $semester_aktif->semester->nama_semester,
                                'id_status_mahasiswa' => 'M',
                                'nama_status_mahasiswa' => 'Kampus Merdeka',
                                'ips'=> '0.00',
                                'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                                'sks_semester'=> $total_sks,
                                'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                                'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
                                'id_pembiayaan' => 2,
                                'status_sync' => 'belum sync',
                            ]);
                        }
                    }else if($tagihan){
                        $peserta = AktivitasKuliahMahasiswa::create([
                            'feeder' => 0,
                            'id_registrasi_mahasiswa' => $id_reg,
                            'nim' => $riwayat_pendidikan->nim,
                            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                            'id_prodi' => $riwayat_pendidikan->id_prodi,
                            'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                            'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                            'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                            'id_semester'=> $semester_aktif->id_semester,
                            'nama_semester'=> $semester_aktif->semester->nama_semester,
                            'id_status_mahasiswa' => 'M',
                            'nama_status_mahasiswa' => 'Kampus Merdeka',
                            'ips'=> '0.00',
                            'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                            'sks_semester'=> $total_sks,
                            'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                            'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
                            'id_pembiayaan' => 1,
                            'status_sync' => 'belum sync',
                        ]);
                    }else{
                        $peserta = AktivitasKuliahMahasiswa::create([
                            'feeder' => 0,
                            'id_registrasi_mahasiswa' => $id_reg,
                            'nim' => $riwayat_pendidikan->nim,
                            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                            'id_prodi' => $riwayat_pendidikan->id_prodi,
                            'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                            'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                            'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                            'id_semester'=> $semester_aktif->id_semester,
                            'nama_semester'=> $semester_aktif->semester->nama_semester,
                            'id_status_mahasiswa' => 'M',
                            'nama_status_mahasiswa' => 'Kampus Merdeka',
                            'ips'=> '0.00',
                            'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                            'sks_semester'=> $total_sks,
                            'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                            'biaya_kuliah_smt' => 0,
                            'id_pembiayaan' => NULL,
                            'status_sync' => 'belum sync',
                        ]);
                    }
                }else{
                    if($beasiswa){
                        if($beasiswa->id_pembiayaan == '3'){
                            $peserta = AktivitasKuliahMahasiswa::create([
                                'feeder' => 0,
                                'id_registrasi_mahasiswa' => $id_reg,
                                'nim' => $riwayat_pendidikan->nim,
                                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                                'id_prodi' => $riwayat_pendidikan->id_prodi,
                                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                                'id_semester'=> $semester_aktif->id_semester,
                                'nama_semester'=> $semester_aktif->semester->nama_semester,
                                'id_status_mahasiswa' => 'A',
                                'nama_status_mahasiswa' => 'Aktif',
                                'ips'=> '0.00',
                                'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                                'sks_semester'=> $total_sks,
                                'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                                'biaya_kuliah_smt' => 0,
                                'id_pembiayaan' => 3,
                                'status_sync' => 'belum sync',
                            ]);
                        }else if($beasiswa->id_pembiayaan == '2' && $tagihan){
                            $peserta = AktivitasKuliahMahasiswa::create([
                                'feeder' => 0,
                                'id_registrasi_mahasiswa' => $id_reg,
                                'nim' => $riwayat_pendidikan->nim,
                                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                                'id_prodi' => $riwayat_pendidikan->id_prodi,
                                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                                'id_semester'=> $semester_aktif->id_semester,
                                'nama_semester'=> $semester_aktif->semester->nama_semester,
                                'id_status_mahasiswa' => 'A',
                                'nama_status_mahasiswa' => 'Aktif',
                                'ips'=> '0.00',
                                'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                                'sks_semester'=> $total_sks,
                                'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                                'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
                                'id_pembiayaan' => 2,
                                'status_sync' => 'belum sync',
                            ]);
                        }
                    }else if($tagihan){
                        $peserta = AktivitasKuliahMahasiswa::create([
                            'feeder' => 0,
                            'id_registrasi_mahasiswa' => $id_reg,
                            'nim' => $riwayat_pendidikan->nim,
                            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                            'id_prodi' => $riwayat_pendidikan->id_prodi,
                            'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                            'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                            'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                            'id_semester'=> $semester_aktif->id_semester,
                            'nama_semester'=> $semester_aktif->semester->nama_semester,
                            'id_status_mahasiswa' => 'A',
                            'nama_status_mahasiswa' => 'Aktif',
                            'ips'=> '0.00',
                            'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                            'sks_semester'=> $total_sks,
                            'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                            'biaya_kuliah_smt' => $tagihan->total_nilai_tagihan,
                            'id_pembiayaan' => 1,
                            'status_sync' => 'belum sync',
                        ]);
                    }else{
                        $peserta = AktivitasKuliahMahasiswa::create([
                            'feeder' => 0,
                            'id_registrasi_mahasiswa' => $id_reg,
                            'nim' => $riwayat_pendidikan->nim,
                            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                            'id_prodi' => $riwayat_pendidikan->id_prodi,
                            'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                            'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                            'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                            'id_semester'=> $semester_aktif->id_semester,
                            'nama_semester'=> $semester_aktif->semester->nama_semester,
                            'id_status_mahasiswa' => 'A',
                            'nama_status_mahasiswa' => 'Aktif',
                            'ips'=> '0.00',
                            'ipk'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                            'sks_semester'=> $total_sks,
                            'sks_total'=> !$transkrip && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                            'biaya_kuliah_smt' => 0,
                            'id_pembiayaan' => NULL,
                            'status_sync' => 'belum sync',
                        ]);
                    }
                }
            }

            DB::commit();

            $result = [
                'status' => 'success',
                'nim' => $riwayat_pendidikan->nim,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'gagal',
                'nim' => $riwayat_pendidikan->nim,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            ];

            return $result;


        }

        return $result;
    }
}
