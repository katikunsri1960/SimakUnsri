<?php

namespace App\Models;

use App\Models\Dosen\BiodataDosen as Dosen;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PejabatFakultas extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'pejabat_fakultas';

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }
}
