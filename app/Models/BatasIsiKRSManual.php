<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BatasIsiKRSManual extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'batas_isi_krs_manual';

    protected $appends = ['status_text', 'terakhir_update'];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
    }

    public function getStatusTextAttribute()
    {
        $status_bayar = [
            0 => 'Belum Bayar',
            1 => 'Lunas',
            2 => 'Beasiswa',
            3 => 'Tunda Bayar',
        ];

        return $status_bayar[$this->status_bayar];
    }

    public function scopeFilter($query, $request)
    {
        if($request->has('id_semester') && $request->id_semester != '') {
            $query->where('id_semester', $request->id_semester);
        } else {
            $semester_aktif = SemesterAktif::first()->id_semester;
            $query->where('id_semester', $semester_aktif);
        }

        return $query;
    }

    public function getTerakhirUpdateAttribute()
    {
        // update_at with d F Y H:i
        return $this->updated_at->format('d F Y (H:i)');
    }
}
