<?php

namespace App\Models\Perkuliahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiKomponenEvaluasi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function nilai_perkuliahan()
    {
        return $this->belongsTo(NilaiPerkuliahan::class, 'id_kelas_kuliah', 'id_kelas');
    }
}
