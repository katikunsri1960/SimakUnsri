<?php

namespace App\Models\Perkuliahan;

use App\Models\Dosen\BiodataDosen;
use App\Models\Referensi\KategoriKegiatan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BimbingMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function aktivitas_mahasiswa()
    {
        return $this->belongsTo(AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function kategori_kegiatan()
    {
        return $this->belongsTo(KategoriKegiatan::class, 'id_kategori_kegiatan', 'id_kategori_kegiatan');
    }

    public function dosen()
    {
        return $this->belongsTo(BiodataDosen::class, 'id_dosen', 'id_dosen')->orderBy('pembimbing_ke');
    }
}
