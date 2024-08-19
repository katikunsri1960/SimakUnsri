<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeasiswaMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function jenis_beasiswa()
    {
        return $this->belongsTo(JenisBeasiswaMahasiswa::class, 'id_jenis_beasiswa');
    }
}
