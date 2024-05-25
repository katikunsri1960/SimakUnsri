<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_fakultas',
    ];

    public function jurusan()
    {
        return $this->hasMany(Jurusan::class, 'id_fakultas', 'id');
    }

    public function prodi()
    {
        $this->hasMany(ProgramStudi::class, 'id_fakultas', 'id');
    }
}
