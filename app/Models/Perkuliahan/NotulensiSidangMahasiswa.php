<?php

namespace App\Models\Perkuliahan;

use App\Models\Dosen\BiodataDosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotulensiSidangMahasiswa extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'notulensi_sidang_mahasiswa';

    public function dosen()
    {
        return $this->belongsTo(BiodataDosen::class, 'id_dosen', 'id_dosen');
    }
}
