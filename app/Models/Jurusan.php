<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function prodi()
    {
        return $this->hasMany(ProgramStudi::class, 'id_jurusan', 'jurusan_id');
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas', 'id');
    }
}
