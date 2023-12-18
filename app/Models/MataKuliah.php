<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function matkul_kurikulum()
    {
        return $this->hasMany(MatkulKurikulum::class, 'id_matkul', 'id_matkul');
    }
}
