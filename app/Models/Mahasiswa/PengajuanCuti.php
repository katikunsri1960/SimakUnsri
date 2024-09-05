<?php

namespace App\Models\Mahasiswa;

use App\Models\ProgramStudi;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Factories\HasFactory;
>>>>>>> 73a4f26dd55de91cfc9f92e7de553431904fd0f2
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PengajuanCuti extends Model
{
    use HasFactory;
    protected $table = 'cuti_kuliahs';
    protected $guarded = [];

<<<<<<< HEAD
    public function riwayat_pendidikan()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }
    
=======
    public function riwayat()
    {
        return $this->belongsTo(RiwayatPendidikan::class, 'id_registrasi_mahasiswa', 'id_registrasi_mahasiswa');
    }

>>>>>>> 73a4f26dd55de91cfc9f92e7de553431904fd0f2
    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }
}
