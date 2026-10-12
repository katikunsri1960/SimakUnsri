<?php

namespace App\Models\Mahasiswa;

use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Referensi\JenisPrestasi;
use App\Models\Referensi\TingkatPrestasi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SKPI;
class PrestasiMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function jenis_prestasi()
    {
        return $this->belongsTo(JenisPrestasi::class, 'id_jenis_prestasi', 'id_jenis_prestasi');
    }

    public function tingkat_prestasi()
    {
        return $this->belongsTo(TingkatPrestasi::class, 'id_tingkat_prestasi', 'id_tingkat_prestasi');
    }

    public function aktivitas_mahasiswa()
    {
        return $this->belongsTo(AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function biodata_mahasiswa()
    {
        return $this->belongsTo(BiodataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function riwayat_pendidikan()
{
    return $this->hasOne(RiwayatPendidikan::class, 'id_mahasiswa', 'id_mahasiswa')
        ->whereNull('id_jenis_keluar')
        ->latestOfMany('id_periode_masuk'); // Mengambil 1 record riwayat aktif dengan periode terbaru
}

    public function skpi()
    {
        return $this->hasOne(SKPI::class, 'id_prestasi', 'id_prestasi');
    }
}
