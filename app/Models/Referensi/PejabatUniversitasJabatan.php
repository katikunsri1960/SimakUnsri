<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PejabatUniversitasJabatan extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function pejabat()
    {
        return $this->hasOne(PejabatUniversitas::class, 'jabatan_id');
    }
}
