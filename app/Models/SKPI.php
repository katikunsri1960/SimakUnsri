<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;


class SKPI extends Model
{
    use HasFactory;

    protected $table = 'skpi_data';

    protected $guarded = ['id'];

    /**
     * Relasi ke riwayat pendidikan (mahasiswa)
     */
    public function riwayat()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    /**
     * Relasi ke riwayat pendidikan (mahasiswa)
     */
    public function wisuda()
    {
        return $this->belongsTo(Wisuda::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }


    /**
     * Relasi ke jenis kegiatan SKPI
     */
    public function jenisSkpi()
    {
        return $this->belongsTo(SkpiJenisKegiatan::class, 'id_jenis_skpi', 'id');
    }
}