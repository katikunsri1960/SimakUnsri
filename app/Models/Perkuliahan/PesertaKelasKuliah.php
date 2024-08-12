<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\SemesterAktif;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
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

        if (now()->isBefore($semester_aktif->krs_mulai) || now()->isAfter($semester_aktif->krs_selesai)) {
            return [
                'status' => 'error',
                'message' => now()->isBefore($semester_aktif->krs_mulai) ? 'Masa Pengisian KRS Belum Dimulai!!' : 'Masa Pengisian KRS Sudah Berakhir!!',
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

        } catch (\Exception $e) {
            return $e->getMessage();
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
                    // dd($transkrip);

        $aktivitas = $db_akt->with('anggota_aktivitas_personal', 'konversi')
                    ->whereHas('anggota_aktivitas_personal', function($query) use ($id_reg) {
                        $query->where('id_registrasi_mahasiswa', $id_reg);
                    })
                    ->where('id_semester', $semester_aktif->id_semester)
                    ->get();

        // dd($aktivitas);
        try {

            DB::beginTransaction();

            if($akm_aktif->feeder == '0'){

                if($akm_aktif->isEmpty())
                {
                    foreach ($aktivitas as $item) {
                        $item->update([
                            'approve_krs' => '1',
                        ]);
                    }

                    foreach ($data as $item) {
                        $item->update([
                            'approved' => '1',
                        ]);
                    }

                    $peserta = AktivitasKuliahMahasiswa::create([
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
                        'ipk'=> $transkrip->ipk,
                        'sks_semester'=> $total_sks,
                        'sks_total'=>$transkrip->total_sks,
                        'biaya_kuliah_smt' => 0,
                        'id_pembiayaan' => NULL,
                        'status_sync' => 'belum sync',
                    ]);
                }else{

                    foreach ($aktivitas as $item) {
                        $item->update([
                            'approve_krs' => '1',
                        ]);
                    }

                    foreach ($data as $item) {
                        $item->update([
                            'approved' => '1',
                        ]);
                    }

                    $peserta = AktivitasKuliahMahasiswa::where('id',$akm_aktif->id)->update([
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
                        'ipk'=> $transkrip->ipk,
                        'sks_semester'=> $total_sks,
                        'sks_total'=>$transkrip->total_sks,
                        'biaya_kuliah_smt' => 0,
                        'id_pembiayaan' => NULL,
                        'status_sync' => 'belum sync',
                    ]);

                }
            }else{
                $result = [
                    'status' => 'error',
                    'message' => 'Data sudah di sinkronisasi ke feeder!',
                ];

                return $result;
            }

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Semua data berhasil disetujui!',
            ];

        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!',
            ];

            return $result;
        }

        return $result;

    }
}
