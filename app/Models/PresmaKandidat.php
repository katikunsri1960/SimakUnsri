<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresmaKandidat extends Model
{
    use HasFactory;

    protected $table = 'presma_kandidats';
    protected $guarded = [];

    /**
     * Relasi ke Data Dukung kandidat (visi, misi, foto, video, berkas)
     */
    public function dataDukung()
    {
        return $this->hasMany(PresmaDataDukung::class, 'no_urut', 'no_urut');
    }
}