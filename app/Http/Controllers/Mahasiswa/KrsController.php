<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Wilayah;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\MatkulKurikulum;

class KrsController extends Controller
{
    public function krs()
    {

        $id_reg = auth()->user()->fk_id;

        $id_prodi = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
            ->pluck('id_prodi')->first();

        $id_semester_kurikulum = ListKurikulum::where('id_prodi', $id_prodi)
            ->max('id_semester');

        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();
            
        $semester_masuk = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->pluck('id_periode_masuk')->first();
        // dd($semester_masuk);

        $matakuliah_kurikulum = MatkulKurikulum::with(['matakuliah', 'matakuliah.prodi'])
            ->where('matkul_kurikulums.id_prodi', $id_prodi)
            ->where('matkul_kurikulums.semester', $semester_ke)
            ->where('matkul_kurikulums.id_semester', '>=', $semester_masuk) // Assuming id_priode_masuk is a column in matkul_kurikulums
            ->orderBy('sks_mata_kuliah')
            ->get();

            // dd($matakuliah_kurikulum);

       $kelas_kuliah = KelasKuliah::where('id_prodi', $id_prodi)
            ->select('id_matkul', 'kode_mata_kuliah', 'nama_mata_kuliah', 'nama_kelas_kuliah', 'tanggal_mulai_efektif' )
            ->groupBy('id_matkul', 'kode_mata_kuliah', 'nama_mata_kuliah', 'nama_kelas_kuliah', 'tanggal_mulai_efektif' )
            ->get();


        return view('mahasiswa.krs.index', compact(
            'matakuliah_kurikulum', 
            'kelas_kuliah',
        ));
    }
}
