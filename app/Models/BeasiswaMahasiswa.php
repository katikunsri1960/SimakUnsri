<?php

namespace App\Models;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Referensi\Pembiayaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeasiswaMahasiswa extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['id_tanggal_mulai_beasiswa', 'id_tanggal_akhir_beasiswa'];

    public function jenis_beasiswa()
    {
        return $this->belongsTo(JenisBeasiswaMahasiswa::class, 'id_jenis_beasiswa');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function pembiayaan()
    {
        return $this->belongsTo(Pembiayaan::class, 'id_pembiayaan', 'id_pembiayaan');
    }

    public function aktivitas_kuliah()
    {
        return $this->hasMany(AktivitasKuliahMahasiswa::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function getIdTanggalMulaiBeasiswaAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_mulai_beasiswa)) ?? '';
    }

    public function setTanggalMulaiBeasiswaAttribute($value)
    {
        $this->attributes['tanggal_mulai_beasiswa'] = date('Y-m-d', strtotime($value));
    }

    public function getIdTanggalAkhirBeasiswaAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_akhir_beasiswa)) ?? '';
    }

    public function setTanggalAkhirBeasiswaAttribute($value)
    {
        $this->attributes['tanggal_akhir_beasiswa'] = date('Y-m-d', strtotime($value));
    }
}
