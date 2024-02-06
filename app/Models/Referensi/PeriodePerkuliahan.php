<?php

namespace App\Models\Referensi;

use App\Models\ProgramStudi;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodePerkuliahan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getTanggalAwalPerkuliahanAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }
    
    public function getTanggalAkhirPerkuliahanAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }
}
