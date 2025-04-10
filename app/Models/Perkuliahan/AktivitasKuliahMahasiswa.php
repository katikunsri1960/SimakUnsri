<?php

namespace App\Models\Perkuliahan;

use App\Models\Referensi\Pembiayaan;
use App\Models\StatusMahasiswa;
use App\Models\Semester;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AktivitasKuliahMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function riwayat_pendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function periode_masuk()
    {
        return $this->belongsTo(Semester::class, 'id_periode_masuk', 'id_semester');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    public function status_mahasiswa()
    {
        return $this->belongsTo(StatusMahasiswa::class, 'id_status_mahasiswa', 'id_status_mahasiswa');
    }

    public function pembiayaan()
    {
        return $this->belongsTo(Pembiayaan::class, 'id_pembiayaan', 'id_pembiayaan');
    }

    public function hitung_sks_per_id($id_reg, $id_semester)
    {
        // Ambil data AKM
        $akm = $this->where('id_registrasi_mahasiswa', $id_reg)
            ->where('id_semester', $id_semester)
            ->first();

        if (!$akm) {
            return ['status' => 'error', 'message' => 'Data tidak ditemukan'];
        }

        // Konstanta untuk batas SKS
        $MAX_SKS = 24;

        // Hitung total SKS transkrip
        $total_sks_transkrip = TranskripMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
            ->whereNotIn('nilai_huruf', ['F', ''])
            ->sum('sks_mata_kuliah');

        // Hitung SKS semester dari berbagai sumber
        $sks_semester = $this->hitung_sks_semester($id_reg, $id_semester);

        // Validasi batas SKS
        if ($sks_semester > $MAX_SKS) {
            return ['status' => 'error', 'message' => 'Mahasiswa ini melampaui batas SKS semester (24 sks)'];
        }

        // Update data AKM
        try {
            DB::beginTransaction();

            $akm->update([
                'sks_semester' => $sks_semester,
                'sks_total' => $total_sks_transkrip,
            ]);

            DB::commit();
            return ['status' => 'success', 'message' => 'Data berhasil diupdate'];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['status' => 'error', 'message' => 'Data Gagal Diupdate! ' . $th->getMessage()];
        }
    }

    /**
     * Hitung total SKS semester dari berbagai sumber.
     */
    private function hitung_sks_semester($id_reg, $id_semester)
    {
        // SKS dari kelas kuliah
        $sks_kelas = PesertaKelasKuliah::where('peserta_kelas_kuliahs.id_registrasi_mahasiswa', $id_reg)
            ->join('kelas_kuliahs as kk', 'kk.id_kelas_kuliah', '=', 'peserta_kelas_kuliahs.id_kelas_kuliah')
            ->join('mata_kuliahs as mk', 'mk.id_matkul', '=', 'kk.id_matkul')
            ->where('kk.id_semester', $id_semester)
            ->where('peserta_kelas_kuliahs.approved', 1)
            ->sum('mk.sks_mata_kuliah');

        // SKS dari aktivitas mahasiswa
        $sks_aktivitas = AnggotaAktivitasMahasiswa::where('anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', $id_reg)
            ->join('aktivitas_mahasiswas as am', 'am.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->join('mata_kuliahs as mk', 'mk.id_matkul', '=', 'am.mk_konversi')
            ->where('am.id_semester', $id_semester)
            ->whereNotNull('am.mk_konversi')
            ->sum('mk.sks_mata_kuliah');

        // SKS dari MBKM
        $sks_mbkm = AnggotaAktivitasMahasiswa::where('anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', $id_reg)
            ->join('aktivitas_mahasiswas as am', 'am.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->where('am.id_semester', $id_semester)
            ->whereNotNull('am.sks_aktivitas')
            ->sum('am.sks_aktivitas');

        // Total SKS semester
        return $sks_kelas + $sks_aktivitas + $sks_mbkm;
    }

}
