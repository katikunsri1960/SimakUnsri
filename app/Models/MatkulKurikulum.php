<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatkulKurikulum extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function kurikulum()
    {
        return $this->belongsTo(ListKurikulum::class, 'id_kurikulum', 'id_kurikulum');
    }

    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    }
}
