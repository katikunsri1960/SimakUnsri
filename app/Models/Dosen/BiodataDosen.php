<?php

namespace App\Models\Dosen;

use App\Models\Wilayah;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiodataDosen extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'id_wilayah', 'id_wilayah');
    }

    public function getJenisKelaminAttribute($value)
    {
        switch ($value) {
            case 'L':
                return 'Laki-laki';
            case 'P':
                return 'Perempuan';
            default:
                return 'Lainnya';
        }
    }

    public function getIdTanggalLahirAttribute()
    {
        return date('d-m-Y', strtotime($this->tanggal_lahir));
    }

    public function list_dosen()
    {
        return $this->select('id_dosen', 'nama_dosen', 'nidn', 'jenis_kelamin', 'nama_agama', 'nama_status_aktif', 'tanggal_lahir')
                    ->where('id_jenis_sdm', 12)->get();
    }
}
