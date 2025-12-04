<?php

namespace App\Models\Mahasiswa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Mahasiswa\LulusDo;
use App\Models\SemesterAktif;
use App\Models\Semester;
use App\Models\Wisuda;
use Illuminate\Database\Eloquent\Model;

class PisnMahasiswa extends Model
{
    use HasFactory;
    protected $table = 'pisn_mahasiswas';
    protected $guarded = [];

    public function lulus_do()
    {
        return $this->belongsTo(LulusDo::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

    public function wisuda()
    {
        return $this->belongsTo(Wisuda::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
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
}
