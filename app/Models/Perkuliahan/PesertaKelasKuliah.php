<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\SemesterAktif;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Connection\Registrasi;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PesertaKelasKuliah extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function matkul()
    {
        $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    }

    public function nilai_perkuliahan()
    {
        return $this->hasMany(NilaiPerkuliahan::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function batal_approve($id_reg)
    {
        $semester_aktif = SemesterAktif::first();

        if (now()->isBefore($semester_aktif->krs_mulai)) {
            return [
                'status' => 'error',
                'message' => 'Masa Pengisian KRS Belum Dimulai!!',
            ];
        }

        if (now()->isBefore($semester_aktif->tanggal_mulai_kprs)) {
            return [
                'status' => 'error',
                'message' => 'Masa KPRS Belum Dimulai. Pembatalan hanya bisa dilakukan saat masa KPRS!!',
            ];
        }

        if (now()->isAfter($semester_aktif->tanggal_akhir_kprs)) {
            return [
                'status' => 'error',
                'message' => 'Masa Pengisian KRS & KPRS Telah Berakhir!!',
            ];
        }


        $data = $this->with(['kelas_kuliah', 'kelas_kuliah.matkul'])
                    ->whereHas('kelas_kuliah', function($query) use ($semester_aktif) {
                        $query->where('id_semester', $semester_aktif->id_semester);
                    })
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->orderBy('kode_mata_kuliah')
                    ->get();

        $db_akt = new AktivitasMahasiswa();

        $aktivitas = $db_akt->with('anggota_aktivitas_personal', 'konversi')
                        ->whereHas('anggota_aktivitas_personal', function($query) use ($id_reg) {
                            $query->where('id_registrasi_mahasiswa', $id_reg);
                        })
                        ->where('id_semester', $semester_aktif->id_semester)
                        ->get();

        try {
            DB::beginTransaction();

            foreach ($aktivitas as $item) {
                $item->update([
                    'approve_krs' => '0',
                ]);
            }

            foreach ($data as $item) {
                $item->update([
                    'approved' => '0',
                ]);
            }

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Persetujuan KRS berhasil dibatalkan!',
            ];

            return $result;

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan! '. $e->getMessage(),
            ];

            return $result;
        }

    }

    public function approve_all($id_reg)
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

        $data_mbkm = PesertaKelasKuliah::with(['kelas_kuliah', 'kelas_kuliah.matkul'])
                    ->whereHas('kelas_kuliah', function($query) use ($semester_aktif,$riwayat_pendidikan) {
                        $query->where('id_semester', $semester_aktif->id_semester)->whereNot('id_prodi', $riwayat_pendidikan->id_prodi);
                    })
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->orderBy('kode_mata_kuliah')
                    ->count();

        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->first();

        $id_test = Registrasi::where('rm_nim', $riwayat_pendidikan->nim)->pluck('rm_no_test');

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

        $transkrip = TranskripMahasiswa::select(
                        DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                        DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
                    )
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->whereNotIn('nilai_huruf', ['F', ''])
                    ->groupBy('id_registrasi_mahasiswa')
                    ->first();

        if ($transkrip->ipk == null && $riwayat_pendidikan->id_periode_masuk != $semester_aktif->id_semester) {
            return [
                'status' => 'error',
                'message' => 'IPK Transkrip Mahasiswa ini belum dicheklist!! Harap menghubungi Admin Program Studi untuk melakukan perbaikan data!!',
            ];
        }

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
                    $item->update([
                        'approved' => '1',
                        'tanggal_approve' => date('Y-m-d')
                    ]);
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
                    $item->update([
                        'approved' => '1',
                        'tanggal_approve' => date('Y-m-d')
                    ]);
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
                'message' => 'Semua data berhasil disetujui!',
            ];

        } catch (\Exception $e) {

            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!'. $e->getMessage(),
            ];

            return $result;


        }

        return $result;

    }
}
