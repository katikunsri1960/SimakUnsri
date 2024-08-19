<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SemesterAktif extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['id_batas_isi_nilai'];

    public function getKrsMulaiAttribute($value)
    {
        return date('d-m-Y', strtotime($value)) ?? '';
    }

    public function setKrsMulaiAttribute($value)
    {
        $this->attributes['krs_mulai'] = date('Y-m-d', strtotime($value));
    }

    public function getKrsSelesaiAttribute($value)
    {
        return date('d-m-Y', strtotime($value)) ?? '';
    }

    public function setKrsSelesaiAttribute($value)
    {
        $this->attributes['krs_selesai'] = date('Y-m-d', strtotime($value));
    }

    public function getIdBatasIsiNilaiAttribute()
    {
        return date('d-m-Y', strtotime($this->batas_isi_nilai)) ?? '';
    }

    public function getTanggalMulaiKprsAttribute($value)
    {
        return date('d-m-Y', strtotime($value)) ?? '';
    }

    public function setTanggalMulaiKprsAttribute($value)
    {
        $this->attributes['tanggal_mulai_kprs'] = date('Y-m-d', strtotime($value));
    }

    public function getTanggalAkhirKprsAttribute($value)
    {
        return date('d-m-Y', strtotime($value)) ?? '';
    }

    public function setTanggalAkhirKprsAttribute($value)
    {
        $this->attributes['tanggal_akhir_kprs'] = date('Y-m-d', strtotime($value));
    }

    public function getBatasBayarUktAttribute($value)
    {
        return date('d-m-Y', strtotime($value)) ?? '';
    }

    public function setBatasBayarUktAttribute($value)
    {
        $this->attributes['batas_bayar_ukt'] = date('Y-m-d', strtotime($value));
    }

    public function setBatasIsiNilaiAttribute($value)
    {
        $this->attributes['batas_isi_nilai'] = date('Y-m-d', strtotime($value));
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }
}
