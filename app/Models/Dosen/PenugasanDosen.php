<?php

namespace App\Models\Dosen;

use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenugasanDosen extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function biodata()
    {
        return $this->belongsTo(BiodataDosen::class, 'id_dosen', 'id_dosen');
    }
}
