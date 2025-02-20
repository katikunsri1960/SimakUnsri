<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeWisuda extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = [
        'id_tanggal_wisuda',
        'id_tanggal_mulai_daftar',
        'id_tanggal_akhir_daftar',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeByYear($query, $year)
    {
        return $query->whereYear('tanggal_wisuda', $year);
    }

    public function getIdTanggalWisudaAttribute()
    {
        return Carbon::parse($this->tanggal_wisuda)->locale('id')->translatedFormat('d F Y');
    }

    public function getIdTanggalMulaiDaftarAttribute()
    {
        return Carbon::parse($this->tanggal_mulai_daftar)->locale('id')->translatedFormat('d F Y');
    }

    public function getIdTanggalAkhirDaftarAttribute()
    {
        return Carbon::parse($this->tanggal_akhir_daftar)->locale('id')->translatedFormat('d F Y');
    }
}
