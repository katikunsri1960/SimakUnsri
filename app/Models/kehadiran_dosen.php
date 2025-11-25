<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kehadiran_dosen extends Model
{
    use HasFactory;
    protected $table = 'kehadiran_dosen';
    protected $fillable = [
        'kode_mata_kuliah',
        'nama_kelas',
        'id_kelas_kuliah',
        'nama_mk',
        'session_id',
        'session_date',
        'timemodified',
        'lasttaken',
        'deskripsi_sesi',
        'id_kehadiran',

    ];
}
