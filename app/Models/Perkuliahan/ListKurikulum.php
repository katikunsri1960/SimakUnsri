<?php

namespace App\Models\Perkuliahan;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListKurikulum extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function matkul_kurikulum()
    {
        return $this->hasMany(MatkulKurikulum::class, 'id_kurikulum', 'id_kurikulum')->orderBy('semester', 'asc');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function mata_kuliah()
    {
        return $this->hasManyThrough(
            MataKuliah::class,
            MatkulKurikulum::class,
            'id_kurikulum', // Foreign key on MatkulKurikulum table...
            'id_matkul', // Foreign key on MataKuliah table...
            'id_kurikulum', // Local key on ListKurikulum table...
            'id_matkul' // Local key on MatkulKurikulum table...
        );
    }
}
