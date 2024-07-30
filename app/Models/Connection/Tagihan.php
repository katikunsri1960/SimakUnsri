<?php

namespace App\Models\Connection;

use App\Models\Semester;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tagihan extends Model
{
    use HasFactory;
    protected $connection = 'keu_con'; // Koneksi keuangan
    protected $table = 'tagihan';

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_record_tagihan', 'id_record_tagihan');
    }

    public function registrasi()
    {
        return $this->hasOne(Registrasi::class, 'rm_nim', 'nomor_pembayaran');
    }

    // Accessor untuk memformat kode_periode
    public function getFormattedKodePeriodeAttribute()
    {
        $kode_periode = $this->attributes['kode_periode'];
        $year = substr($kode_periode, 0, 4);
        $semester_code = substr($kode_periode, -1);

        switch ($semester_code) {
            case '1':
                $semester = 'Ganjil';
                break;
            case '2':
                $semester = 'Genap';
                break;
            case '3':
                $semester = 'Pendek';
                break;
            default:
                $semester = 'Tidak Diisi';
                break;
        }

        $next_year = $year + 1;
        return "{$year}/{$next_year} {$semester}";
    }
}
