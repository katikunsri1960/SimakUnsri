<?php

namespace App\Models\Perkuliahan;

use App\Models\Semester;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\SemesterAktif;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class PesertaKelasKuliah extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function matkul()
    {
        $this->belongsTo(MataKuliah::class, 'id_matkul', 'id_matkul');
    }

    public function nilai_perkuliahan()
    {
        return $this->hasMany(NilaiPerkuliahan::class, 'id_kelas_kuliah', 'id_kelas_kuliah');
    }

    public function approve_all($id)
    {
        $semester = SemesterAktif::first()->id_semester;
        $data = PesertaKelasKuliah::with(['kelas_kuliah', 'kelas_kuliah.matkul'])
                ->whereHas('kelas_kuliah', function($query) use ($semester) {
                    $query->where('id_semester', $semester);
                })
                ->where('id_registrasi_mahasiswa', $id)
                ->orderBy('kode_mata_kuliah')
                ->get();
        try {

            DB::beginTransaction();

            foreach ($data as $item) {
                $item->update([
                    'approved' => '1',
                ]);
            }

            DB::commit();

            $result = [
                'status' => 'success',
                'message' => 'Semua data berhasil disetujui!',
            ];

        } catch (\Exception $e) {
            return $e->getMessage();
            DB::rollBack();

            $result = [
                'status' => 'error',
                'message' => 'Terjadi kesalahan!',
            ];

            return $result;
        }

        return $result;

    }
}
