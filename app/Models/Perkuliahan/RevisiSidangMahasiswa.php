<?php

namespace App\Models\Perkuliahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Database\Eloquent\Model;

class RevisiSidangMahasiswa extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'revisi_sidang_mahasiswa';

    public function dosen()
    {
        return $this->belongsTo(BiodataDosen::class, 'id_dosen', 'id_dosen');
    }
}
