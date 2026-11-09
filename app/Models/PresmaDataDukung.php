<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresmaDataDukung extends Model
{
    use HasFactory;

    protected $table = 'presma_data_dukungs';
    protected $guarded = [];

    /**
     * Relasi balik ke PresmaKandidat (berdasarkan no_urut)
     */
    public function kandidat()
    {
        return $this->hasMany(PresmaKandidat::class, 'no_urut', 'no_urut');
    }
}