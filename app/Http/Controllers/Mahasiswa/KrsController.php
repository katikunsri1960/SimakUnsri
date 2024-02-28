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
use App\Models\SemesterAktif;

class KrsController extends Controller
{
    // public function krs_lama()
    // {

    //     $id_reg = auth()->user()->fk_id;

    //     $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
    //                         ->first();

    //     $semester_aktif = Semester::select('*',DB::raw('RIGHT(id_semester, 1) as kode_semester'))//Ambiil Nilai apling belakang id_semester
    //                     ->where('id_semester', '20231')
    //                     // ->pluck('kode_semester')
    //                     // ->limit(50)
    //                     ->first();
        
    //     $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();
    //     // dd($riwayat_pendidikan);

    //     $matakuliah =  MataKuliah::with(['kelas_kuliah' => function ($query) 
    //                 use ($riwayat_pendidikan) 
    //                 {
    //                     $query
    //                             ->where('id_prodi', $riwayat_pendidikan->id_prodi)
    //                             // ->withCount('dosen_pengajar')
    //                             ;
    //                 },'kelas_kuliah.dosen_pengajar',  ])
                    
    //                 ->Join('kelas_kuliahs', 'mata_kuliahs.id_matkul', '=', 'kelas_kuliahs.id_matkul')
    //                 ->select(
    //                     'mata_kuliahs.id_matkul',
    //                     'mata_kuliahs.kode_mata_kuliah',
    //                     'mata_kuliahs.nama_mata_kuliah',
    //                     'mata_kuliahs.sks_mata_kuliah',
    //                     DB::raw("RIGHT(id_semester, 1) AS kode_semester_kelas")
    //                 )
    //                 ->withCount('kelas_kuliah')
    //                 ->where(DB::raw("RIGHT(id_semester, 1)"), '=', 1)
    //                 // ->where('kode_semester_kelas',1) 
    //                 ->where('mata_kuliahs.id_prodi', $riwayat_pendidikan->id_prodi)
                    
    //                 ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah', 'mata_kuliahs.nama_mata_kuliah', 'mata_kuliahs.sks_mata_kuliah', 'id_semester',)
                    
    //                 // ->where('mata_kuliahs.nama_mata_kuliah', 'PENGUKURAN TEKNIK')
                    
    //                 ->whereNotNull('id_semester')
    //                 ->whereNot('sks_mata_kuliah', [0])
    //                 // ->orderBy('kelas_kuliah_count')
    //                 ->orderBy('sks_mata_kuliah')
    //                 ->distinct()
    //                 // ->limit(100) 
    //                 ->get();
    //                 // dd($matakuliah);

    //     $kelas_kuliah = KelasKuliah::with(['dosen_pengajar'])
    //                 ->withCount('peserta_kelas')
    //                 ->where('id_prodi', $riwayat_pendidikan->id_prodi)
    //                 ->where('id_semester',  $semester_aktif->id_semester) 
    //                 // ->where('nama_mata_kuliah', 'PENGUKURAN TEKNIK')
    //                 ->get();

    //     $krs = PesertaKelasKuliah::leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
    //                 ->leftJoin('mata_kuliahs', 'peserta_kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
    //                 ->where('id_registrasi_mahasiswa', $id_reg)
    //                 ->where('id_semester', $semester_aktif->id_semester)
    //                 // ->limit(10)
    //                 ->get();
    //                 // dd($peserta_kelas);
                

    //     return view('mahasiswa.krs.index', compact(
    //         'matakuliah', 
    //         'kelas_kuliah',
    //         'semester_aktif',
    //         'krs',
    //         // 'totalPeserta',
    //     ));
    // }



    public function krs()
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                            ->first();
        
        $prodi_id = $riwayat_pendidikan->id_prodi;
        // dd($prodi_id);

        $semester_aktif = SemesterAktif::select('*',DB::raw('RIGHT(id_semester, 1) as kode_semester'))//Ambiil Nilai paling belakang id_semester untuk penentu ganjil genap
                        ->first();
                        // dd($semester_aktif);
        
        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();
        // dd($semester_aktif);

        //ambil matakuliah ganjil atau genap, dari matakuliah kurikulum where = semester ke

        // $semester_aktif = Semester::where('id_semester','=','20231')->where('a_periode_aktif','=','1')->get();
        
        $data_univ = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                            ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.kode_mata_kuliah=mata_kuliahs.kode_mata_kuliah and kelas_kuliahs.id_prodi='".$prodi_id."' and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                            ->whereIn('mata_kuliahs.kode_mata_kuliah', array('UNI1001','UNI1002','UNI1003','UNI1004'))
                            ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->get();
        // dd($data_univ);
        if(substr($semester_aktif['id_semester'],-1) == '1'){
            $matakuliah = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                            ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                            ->where('mata_kuliahs.id_prodi', $prodi_id)
                            ->where(DB::raw("matkul_kurikulums.semester % 2"),'!=',0)
                            ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->orderBy('matkul_kurikulums.semester')
                            ->orderBy('matkul_kurikulums.sks_mata_kuliah')
                            ->orderBy('jumlah_kelas_kuliah', 'DESC')
                            // ->limit(10)
                            ->get();
        }else if(substr($semester_aktif['id_semester'],-1) == '2'){
            $matakuliah = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                            ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                            ->where('mata_kuliahs.id_prodi', $prodi_id)
                            ->where(DB::raw("matkul_kurikulums.semester % 2"),'=',0)
                            ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->orderBy('matkul_kurikulums.semester')
                            ->orderBy('matkul_kurikulums.sks_mata_kuliah')
                            ->orderBy('jumlah_kelas_kuliah', 'DESC')
                            // ->limit(10)
                            ->get();
        }else if(substr($semester_aktif['id_semester'],-1) == '3'){
            $matakuliah = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                            ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                            ->where('mata_kuliahs.id_prodi', $prodi_id)
                            ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->orderBy('matkul_kurikulums.semester')
                            ->orderBy('matkul_kurikulums.sks_mata_kuliah')
                            ->orderBy('jumlah_kelas_kuliah', 'DESC')
                            // ->limit(10)
                            ->get();
        }else{
            return redirect()->back()->with('error', 'Semester tidak terdata');
        }

       $krs = PesertaKelasKuliah::leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                    ->leftJoin('mata_kuliahs', 'peserta_kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    // ->limit(10)
                    ->get();
                    // dd($peserta_kelas);
                

        return view('mahasiswa.krs.index', compact(
            'matakuliah', 
            // 'kelasKuliah',
            'semester_aktif',
            'krs',
            // 'totalPeserta',
        ));
    }

    public function get_kelas_kuliah(Request $request)
    {
        // Ambil id_matkul dari permintaan Ajax
        $idMatkul = $request->get('id_matkul');
        // dd($idMatkul);

        $id_reg = auth()->user()->fk_id;
        // $prodi_id = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::select('*',DB::raw('LEFT(id_semester, 4) as id_tahun_ajaran'), DB::raw('RIGHT(id_semester, 1) as kode_semester'))//Ambiil Nilai paling belakang id_semester untuk penentu ganjil genap
                        ->first();
                        // dd($semester_aktif);

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
        ->first();
    
        // Ambil data kelas kuliah sesuai dengan kebutuhan Anda
        $kelasKuliah = KelasKuliah::with(['dosen_pengajar.dosen'])
                    ->withCount('peserta_kelas')
                    ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                    ->where('id_semester',  $semester_aktif->id_semester) 
                    ->where('id_matkul', $idMatkul)
                    ->orderBy('nama_kelas_kuliah')
                    ->get();

                        // dd($kelasKuliah);
        
        
        // Sertakan data yang ingin Anda kirim ke tampilan
        return response()->json($kelasKuliah);
    }

    public function storeKelasKuliah(Request $request)
    {
        $idKelas = $request->input('id_kelas_kuliah');
        $idMahasiswa = Auth::user()->fk_id; // Sesuaikan dengan cara Anda mendapatkan ID mahasiswa

        // Lakukan logika penyimpanan ke tabel peserta_kelas_kuliah
        try {
            PesertaKelasKuliah::create([
                'id_kelas_kuliah' => $idKelas,
                'id_mahasiswa' => $idMahasiswa,
                // Sesuaikan dengan kolom-kolom lain yang diperlukan
            ]);

            return response()->json(['message' => 'Kelas berhasil diambil'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengambil kelas. Error: ' . $e->getMessage()], 500);
        }
    }

    public function ambilKelasKuliah(Request $request)
    {
        try {
            $idKelasKuliah = $request->input('id_kelas_kuliah');
            $idReg = auth()->user()->fk_id;

            // Lakukan validasi atau logika bisnis lainnya jika diperlukan

            // Lakukan penyimpanan data
            DB::beginTransaction();

            PesertaKelasKuliah::create([
                'id_kelas_kuliah' => $idKelasKuliah,
                'id_registrasi_mahasiswa' => $idReg,
                // ... (Tambahkan kolom lain jika diperlukan)
            ]);

            // Selesaikan transaksi
            DB::commit();

            // Respon sesuai kebutuhan
            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan
            DB::rollback();

            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }

    public function storePesertaKelasss(Request $request)
    {
        // Validasi request jika diperlukan

        // Simpan data ke tabel peserta_kelas_kuliah
        PesertaKelasKuliah::create([
            'id_kelas_kuliah' => $request->id_kelas_kuliah,
            'nama_kelas_kuliah' => $request->nama_kelas_kuliah,
            'id_registrasi_mahasiswa' => $request->id_registrasi_mahasiswa,
            'id_mahasiswa' => $request->id_mahasiswa,
            'nim' => $request->nim,
            'nama_mahasiswa' => $request->nama_mahasiswa,
            'id_matkul' => $request->id_matkul,
            'kode_mata_kuliah' => $request->kode_mata_kuliah,
            'nama_mata_kuliah' => $request->nama_mata_kuliah,
            'id_prodi' => $request->id_prodi,
            'nama_program_studi' => $request->nama_program_studi,
            'angkatan' => $request->angkatan,
            // Sesuaikan dengan kolom-kolom lainnya
        ]);

        // Berikan respons sesuai kebutuhan
        return response()->json(['message' => 'Kelas berhasil diambil']);
    }

}
