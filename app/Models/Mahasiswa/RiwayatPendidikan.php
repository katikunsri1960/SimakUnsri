<?php

namespace App\Models\Mahasiswa;

use App\Models\Dosen\BiodataDosen;
use App\Models\Semester;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\JalurMasuk;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class RiwayatPendidikan extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $appends = ['angkatan'];

    public function pembimbing_akademik()
    {
        return $this->belongsTo(BiodataDosen::class, 'dosen_pa', 'id_dosen');
    }

    public function kurikulum()
    {
        return $this->belongsTo(ListKurikulum::class, 'id_kurikulum', 'id_kurikulum');
    }

    public function biodata()
    {
        return $this->belongsTo(BiodataMahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function prodi()
    {
        return $this->belongsTo(ProgramStudi::class, 'id_prodi', 'id_prodi');
    }

    public function periode_masuk()
    {
        return $this->belongsTo(Semester::class, 'id_periode_masuk', 'id_semester');
    }

    public function getAngkatanAttribute()
    {
        return substr($this->id_periode_masuk, 0, 4);
    }

    public function getGelombangMasukAttribute()
    {
        return substr($this->id_periode_masuk, 4, 1);
    }

    public function jalur_masuk()
    {
        return $this->belongsTo(JalurMasuk::class, 'id_jalur_daftar', 'id_jalur_masuk');
    }

    public function aktivitas_kuliah()
    {
        return $this->hasMany(AktivitasKuliahMahasiswa::class, 'id_riwayat_pendidikan', 'id_riwayat_pendidikan');
    }

    public function set_kurikulum_angkatan($tahun_angkatan, $id_kurikulum, $prodi)
    {
        try {
            DB::beginTransaction();
            $this->where(DB::raw('LEFT(id_periode_masuk, 4)'), $tahun_angkatan)->where('id_prodi', $prodi)
                ->update(['id_kurikulum' => $id_kurikulum]);

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Kurikulum berhasil diubah'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 'error',
                'message' => 'Kurikulum gagal diubah'
            ];

            return $result;
        }

        return $result;
    }

}
