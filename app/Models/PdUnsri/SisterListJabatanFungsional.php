<?php

namespace App\Models\PdUnsri;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dosen\BiodataDosen;

class SisterListJabatanFungsional extends Model
{
    protected $table = 'sister_list_jabatan_fungsional';

    public $incrementing = false;
    protected $primaryKey = null;
    public $timestamps = false;

    protected $fillable = [
        'id_sdm',
        'id_stat_pegawai',
        'id',
        'jabatan_fungsional',
        'nm_stat_pegawai',
        'sk',
        'tanggal_mulai',
    ];

    public function dosen()
    {
        return $this->belongsTo(BiodataDosen::class, 'id_sdm', 'id_dosen');
    }
}