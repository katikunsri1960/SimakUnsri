<?php

namespace App\Models;

use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenundaanBayar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['status_text', 'terakhir_update'];

    const STATUS = [
        0 => [
            'status' => 'Diajukan',
            'class' => 'fa-user',
        ],
        3 => [
            'status' => 'Disetujui Fakultas',
            'class' => 'fa-user-check text-success',
        ],
        4 => [
            'status' => 'Disetujui BAK',
            'class' => 'fa-check text-success',
        ],
        5 => [
            'status' => 'Ditolak',
            'class' => 'fa-times text-danger',
        ],

    ];

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
        $status = [
            0 => 'Diajukan',
            2 => 'Disetujui Prodi',
            3 => 'Disetujui Fakultas',
            4 => 'Disetujui BAK',
            5 => 'Ditolak',
        ];

        return $status[$this->status];
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
