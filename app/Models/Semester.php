<?php

namespace App\Models;

use App\Models\Perkuliahan\KelasKuliah;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kelas_kuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'id_semester', 'id_semester');
    }
}
