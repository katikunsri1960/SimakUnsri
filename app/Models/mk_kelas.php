<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mk_kelas extends Model
{
    use HasFactory;
    protected $table = 'mk_kelas';
    protected $fillable = [
        'kode_mata_kuliah',
        'nama_kelas_kuliah',
        'kelas_kuliah',
        'id_kelas_kuliah'
    ];
}
