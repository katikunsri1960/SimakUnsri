<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\ProgramStudi;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Wisuda extends Model
{
    use HasFactory;
    protected $table = 'data_wisuda';
    protected $appends = ['id_tanggal_sk_yudisium'];

    // App\Models\Wisuda.php
    protected $fillable = [
        'id_perguruan_tinggi',
        'id_registrasi_mahasiswa',
        'id_prodi',
        'tgl_masuk',
        'tgl_keluar',
        'lama_studi',
        'no_peserta_ujian',
        'sks_diakui',
        'no_ijazah',
        'wisuda_ke',
        'no_sk_yudisium',
        'tgl_sk_yudisium',
        'id_aktivitas',
        'keterangan',
        'angkatan',
        'nim',
        'nama_mahasiswa',
        'kosentrasi',
        'pas_foto',
        'lokasi_kuliah',
        'abstrak_ta',
        'abstrak_file',
        'approved',
    ];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function riwayat_pendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function aktivitas_kuliah()
    {
        return $this->hasMany(AktivitasKuliahMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function aktivitas_mahasiswa()
    {
        return $this->hasManyThrough(AktivitasMahasiswa::class, AnggotaAktivitasMahasiswa::class, 'id_registrasi_mahasiswa', 'id_aktivitas', 'id_registrasi_mahasiswa', 'id_aktivitas');
    }

    public function transkrip_mahasiswa()
    {
        return $this->hasMany(TranskripMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }
}
