<?php

namespace App\Models;

use App\Models\Perkuliahan\AktivitasMahasiswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AsistensiAkhir extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['id_tanggal'];

    public function aktivitas()
    {
        return $this->belongsTo(AktivitasMahasiswa::class, 'id_aktivitas', 'id_aktivitas');
    }

    public function getIdTanggalAttribute()
    {
        // change tanggal from database with format Y-m-d to d-m-Y
        return Carbon::parse($this->tanggal)->format('d-m-Y');
    }
}
