<?php

namespace App\Models;

use App\Models\Mahasiswa\BiodataMahasiswa;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CutiManual extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $table = 'cuti_kuliahs';

     const STATUS = [
         0 => [
            'status' => 'Belum Disetujui',
            'class' => 'fa-user',
         ],
        1 => [
            'status' => 'Disetujui Fakultas',
            'class' => 'fa-user-check text-primary',
        ],
        2 => [
            'status' => 'Disetujui BAK',
            'class' => 'fa-check text-success',
        ],
        3 => [
            'status' => 'Ditolak Fakultas',
            'class' => 'fa-user-times text-danger',
        ],
        4 => [
            'status' => 'Ditolak BAK',
            'class' => 'fa-times text-danger',
        ],
    ];

    // protected $fillable = ['id_registrasi_mahasiswa'];

    protected $appends = ['status_text', 'terakhir_update'];

    public function riwayat()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester', 'id_semester');
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
