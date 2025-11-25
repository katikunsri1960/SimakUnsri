<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kehadiran_mahasiswa extends Model
{
    use HasFactory;
    protected $table = 'kehadiran_mahasiswa';
    protected $fillable = [
        'kode_mata_kuliah',
        'username',
        'nama_kelas',
        'nama_mk',
        'session_id',
        'session_date',
        'deskripsi_sesi',
        'id_kehadiran',
        'status_id',
        'status_mahasiswa'
    ];
}
