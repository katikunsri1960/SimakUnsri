<?php

namespace App\Models;

use App\Models\Perkuliahan\KelasKuliah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RuangPerkuliahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ruang',
        'kapasitas_ruang',
        'lokasi',
        'fakultas_id',
    ];

    public function kelas_kuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'id', 'ruang_perkuliahan_id');
    }


}
