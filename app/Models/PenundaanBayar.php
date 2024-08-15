<?php

namespace App\Models;

use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenundaanBayar extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $appends = ['status_text'];

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
}
