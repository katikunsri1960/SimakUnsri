<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Wilayah;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\MatkulKurikulum;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class KrsController extends Controller
{
    public function krs()
    {

        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

        $semester_aktif = Semester::select('*',DB::raw('RIGHT(id_semester, 1) as kode_semester'))//Ambiil Nilai apling belakang id_semester
                        ->where('id_semester', '20231')
                        // ->pluck('kode_semester')
                        // ->limit(50)
                        ->first();
        
        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();
        // dd($riwayat_pendidikan);

        $matakuliah =  MataKuliah::with(['kelas_kuliah' => function ($query) 
                    use ($riwayat_pendidikan) 
                    {
                        $query
                                ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                                ;
                    },'kelas_kuliah.dosen_pengajar',  ])
                    ->leftJoin('kelas_kuliahs', 'mata_kuliahs.id_matkul', '=', 'kelas_kuliahs.id_matkul')
                    ->select(
                        'mata_kuliahs.id_matkul',
                        'mata_kuliahs.kode_mata_kuliah',
                        'mata_kuliahs.nama_mata_kuliah',
                        'mata_kuliahs.sks_mata_kuliah',
                        DB::raw("RIGHT(id_semester, 1) AS kode_semester_kelas")
                    )
                    ->where(DB::raw("RIGHT(id_semester, 1)"), '=', 1)
                    // ->where('kode_semester_kelas',1) 
                    ->where('mata_kuliahs.id_prodi', $riwayat_pendidikan->id_prodi)
                    
                    ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah', 'mata_kuliahs.nama_mata_kuliah', 'mata_kuliahs.sks_mata_kuliah', 'id_semester',)
                    ->whereNotNull('id_semester')
                    ->whereNot('sks_mata_kuliah', [0])
                    ->orderBy('sks_mata_kuliah')
                    ->distinct()
                    ->limit(100) 
                    ->get();
                    // dd($matakuliah);

       $kelas_kuliah = KelasKuliah::with(['dosen_pengajar'])->withCount('peserta_kelas')
                    ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                    ->where('id_semester',  $semester_aktif->id_semester) 
                    ->get();

        $krs = PesertaKelasKuliah::leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
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
            'krs',
            // 'totalPeserta',
        ));
    }

    public function ambilKrs(Request $request, $id_kelas_kuliah)
    {
        // Lakukan validasi atau logika lainnya sesuai kebutuhan
        // ...

        // Ambil data yang diperlukan
        $kelas_kuliah = KelasKuliah::findOrFail($id_kelas_kuliah);
        $id_reg = auth()->user()->fk_id;

        $mahasiswa = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                            ->first();
                            dd($mahasiswa);
        // $mahasiswa = Auth::user()->mahasiswa; // Pastikan model user memiliki relasi 'mahasiswa'

        // Simpan data ke tabel peserta_kelas_kuliahs
        PesertaKelasKuliah::create([
            'id_kelas_kuliah' => $id_kelas_kuliah,
            'nama_kelas_kuliah' => $kelas_kuliah->nama_kelas_kuliah,
            'id_registrasi_mahasiswa' => Auth::user()->fk_id,
            'id_mahasiswa' => $mahasiswa->id_mahasiswa,
            'nim' => $mahasiswa->nim,
            'nama_mahasiswa' => $mahasiswa->nama_mahasiswa,
            'id_matkul' => $request->input('id_matkul'),
            'kode_mata_kuliah' => $kelas_kuliah->kode_mata_kuliah, 
            'nama_mata_kuliah' => $kelas_kuliah->nama_mata_kuliah, 
            'id_prodi' => $mahasiswa->id_prodi,
            'nama_program_studi' => $mahasiswa->prodi->nama_program_studi,
            'angkatan' => $mahasiswa->angkatan,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Redirect atau berikan respons sesuai kebutuhan
        return redirect()->route('nama-rute-redirect')->with('success', 'KRS berhasil diambil');
    }
}
