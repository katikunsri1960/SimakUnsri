<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkalaNilai extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function getTanggalAkhirEfektifAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    public function getTanggalMulaiEfektifAttribute($value)
    {
        return date('d-m-Y', strtotime($value));
    }

    public function getNilaiIndeksAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getBobotMinimumAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getBobotMaksimumAttribute($value)
    {
        return number_format($value, 2);
    }
}
