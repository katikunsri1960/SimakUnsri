<?php

namespace App\Models\Perkuliahan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomponenEvaluasiKelas extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['persen_bobot_evaluasi'];

    public function getPersenBobotEvaluasiAttribute()
    {
        return $this->bobot_evaluasi * 100;
    }
}
