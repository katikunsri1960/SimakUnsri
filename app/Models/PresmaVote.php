<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;

class PresmaVote extends Model
{
    protected $table = 'presma_votes';

    // Relasi ke RiwayatPendidikan
    public function riwayatPendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }
}