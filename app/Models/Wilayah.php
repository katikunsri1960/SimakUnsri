<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function level()
    {
        return $this->belongsTo(LevelWilayah::class, 'id_level_wilayah', 'id_level_wilayah');
    }

    public function negara()
    {
        return $this->belongsTo(Negara::class, 'id_negara', 'id_negara');
    }

    public function kab_kota()
    {
        return $this->belongsTo(Wilayah::class, 'id_induk_wilayah', 'id_wilayah')->whereNotNull('id_induk_wilayah');
    }
}
