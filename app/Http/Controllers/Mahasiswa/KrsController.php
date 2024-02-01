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
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\MatkulKurikulum;
use App\Models\Perkuliahan\PesertaKelasKuliah;

class KrsController extends Controller
{
    public function krs()
    {

        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

        $semester_aktif = Semester::where('id_semester', '20231')->first();
        
        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();

        $matakuliah =  MataKuliah::leftJoin('kelas_kuliahs', 'mata_kuliahs.id_matkul', '=', 'kelas_kuliahs.id_matkul')
                    ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah', 'mata_kuliahs.nama_mata_kuliah', 'mata_kuliahs.sks_mata_kuliah')
                    ->where('mata_kuliahs.id_prodi', $riwayat_pendidikan->id_prodi)
                    ->where('kelas_kuliahs.id_semester',  $semester_aktif->id_semester) 
                    ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah', 'mata_kuliahs.nama_mata_kuliah', 'mata_kuliahs.sks_mata_kuliah')
                    ->orderBy('sks_mata_kuliah')
                    ->get();

       $kelas_kuliah = KelasKuliah::with(['dosen_pengajar'])
                    ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                    ->where('id_semester',  $semester_aktif->id_semester) 
                    ->select('id_matkul', 'id_kelas_kuliah', 'nama_mata_kuliah', 'nama_kelas_kuliah', 'tanggal_mulai_efektif', 'id_semester', 'jadwal_hari', 'jadwal_jam_mulai', 'jadwal_jam_selesai' )
                    ->groupBy('id_matkul', 'id_kelas_kuliah', 'nama_mata_kuliah', 'nama_kelas_kuliah', 'tanggal_mulai_efektif', 'id_semester', 'jadwal_hari', 'jadwal_jam_mulai', 'jadwal_jam_selesai' )
                    ->orderBy('nama_kelas_kuliah', 'ASC')
                    ->get();

        // $peserta_kelas = PesertaKelasKuliah::where('id_registrasi_mahasiswa', $id_reg)
        //             ->get();
        //             dd($id_reg);

        $peserta_kelas = PesertaKelasKuliah::leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                    ->leftJoin('mata_kuliahs', 'peserta_kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    // ->limit(10)
                    ->get();
                    // dd($peserta_kelas);
                

        return view('mahasiswa.krs.index', compact(
            'matakuliah', 
            'kelas_kuliah',
            'semester_aktif',
            'peserta_kelas',
        ));
    }
}
