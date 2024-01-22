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

    public function penugasan()
    {
        return $this->hasMany(PenugasanDosen::class, 'id_dosen', 'id_dosen');
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

    public function list_dosen($tahun_ajaran = null)
    {
        $tahun_ajaran = $tahun_ajaran ?? (date('m') >= 8 ? date('Y') : date('Y') - 1);

        return $this->leftJoin('penugasan_dosens as p', 'p.id_dosen', '=', 'biodata_dosens.id_dosen')
                    ->select('biodata_dosens.*', 'p.nama_program_studi as prodi', 'p.a_sp_homebase as homebase')
                    ->where('id_jenis_sdm', 12)
                    ->where('p.id_tahun_ajaran', $tahun_ajaran)
                    ->get();
    }
}
