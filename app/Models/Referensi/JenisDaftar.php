<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class JenisDaftar extends Model
{
    protected $table = 'jenis_daftars';
    protected $primaryKey = 'id_jenis_daftar';
    public $timestamps = false;
}