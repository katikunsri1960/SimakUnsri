<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SemesterAktif extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $appends = ['id_mulai_isi_nilai', 'id_batas_isi_nilai', 'id_tanggal_mulai_kprs', 'id_tanggal_akhir_kprs', 'id_krs_mulai', 'id_krs_selesai', 'id_batas_bayar_ukt'];

    // semester allow json

    public function getSemesterAllowAttribute($value)
    {
        return json_decode($value);
    }

    public function setSemesterAllowAttribute($value)
    {
        $this->attributes['semester_allow'] = json_encode($value);
    }

    public function getIdKrsMulaiAttribute()
    {
        return date('d-m-Y', strtotime($this->krs_mulai)) ?? '';
    }

    public function setKrsMulaiAttribute($value)
    {
        $this->attributes['krs_mulai'] = date('Y-m-d', strtotime($value));
    }

    public function getIdKrsSelesaiAttribute()
    {
        return date('d-m-Y', strtotime($this->krs_selesai)) ?? '';
    }

    public function setKrsSelesaiAttribute($value)
    {
        $this->attributes['krs_selesai'] = date('Y-m-d', strtotime($value));
    }

    public function getIdBatasIsiNilaiAttribute()
    {
        return date('d-m-Y', strtotime($this->batas_isi_nilai)) ?? '';
    }

    public function getIdTanggalMulaiKprsAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_mulai_kprs)) ?? '';
    }

    public function setTanggalMulaiKprsAttribute($value)
    {
        $this->attributes['tanggal_mulai_kprs'] = date('Y-m-d', strtotime($value));
    }

    public function getIdTanggalAkhirKprsAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_akhir_kprs)) ?? '';
    }

    public function setTanggalAkhirKprsAttribute($value)
    {
        $this->attributes['tanggal_akhir_kprs'] = date('Y-m-d', strtotime($value));
    }

    public function getIdBatasBayarUktAttribute()
    {
        return date('d-m-Y', strtotime($this->batas_bayar_ukt)) ?? '';
    }

    public function setBatasBayarUktAttribute($value)
    {
        $this->attributes['batas_bayar_ukt'] = date('Y-m-d', strtotime($value));
    }

    public function setBatasIsiNilaiAttribute($value)
    {
        $this->attributes['batas_isi_nilai'] = date('Y-m-d', strtotime($value));
    }

    public function getIdMulaiIsiNilaiAttribute()
    {
        return date('d-m-Y', strtotime($this->mulai_isi_nilai)) ?? '';
    }

    public function setMulaiIsiNilaiAttribute($value)
    {
        $this->attributes['mulai_isi_nilai'] = date('Y-m-d', strtotime($value));
    }

    //TUNDA BAYAR
    public function getIdMulaiTundaBayarAttribute()
    {
        return date('d-m-Y', strtotime($this->mulai_tunda_bayar)) ?? '';
    }

    public function setMulaiTundaBayarAttribute($value)
    {
        $this->attributes['mulai_tunda_bayar'] = date('Y-m-d', strtotime($value));
    }

    public function getIdBatasTundaBayarAttribute()
    {
        return date('d-m-Y', strtotime($this->batas_tunda_bayar)) ?? '';
    }

    public function setBatasTundaBayarAttribute($value)
    {
        $this->attributes['batas_tunda_bayar'] = date('Y-m-d', strtotime($value));
    }

    // PERIODE SO
    public function getIdTglMulaiPengajuanCutiAttribute()
    {
        return date('d-m-Y', strtotime($this->tgl_mulai_pengajuan_cuti)) ?? '';
    }

    public function setTglMulaiPengajuanCutiAttribute($value)
    {
        $this->attributes['tgl_mulai_pengajuan_cuti'] = date('Y-m-d', strtotime($value));
    }

    public function getIdTglSelesaiPengajuanCutiAttribute()
    {
        return date('d-m-Y', strtotime($this->tgl_selesai_pengajuan_cuti)) ?? '';
    }

    public function setTglSelesaiPengajuanCutiAttribute($value)
    {
        $this->attributes['tgl_selesai_pengajuan_cuti'] = date('Y-m-d', strtotime($value));
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }
}
