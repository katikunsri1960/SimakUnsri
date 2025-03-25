<?php

namespace App\Models\Monitoring;

use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonevStatusMahasiswaDetail extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function status()
    {
        $data = [
            'mahasiswa_lewat_semester',
            'do_under_semester_4',
        ];

        return $data;
    }

    public function monevStatusMahasiswa()
    {
        return $this->belongsTo(MonevStatusMahasiswa::class);
    }

    public function riwayat()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }
}
