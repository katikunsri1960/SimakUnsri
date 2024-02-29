<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\SemesterAktif;

class KrsController extends Controller
{
    public function krs()
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                            ->first();
        
        $prodi_id = $riwayat_pendidikan->id_prodi;
        // dd($prodi_id);

        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();
                        // dd($semester_aktif);
        
        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();
        // dd($semester_aktif);

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

    public function ambilKelasKuliah(Request $request)
    {
        try {
            $idKelasKuliah = $request->input('id_kelas_kuliah');
            $id_reg = auth()->user()->fk_id;

            $riwayat_pendidikan = RiwayatPendidikan::with(['periode_masuk'])
                            ->where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

            $kelas_kuliah = KelasKuliah::where('id_kelas_kuliah', $idKelasKuliah)->first();

            // Lakukan penyimpanan data
            DB::beginTransaction();

            $pesertaKelasKuliah = PesertaKelasKuliah::create([
                'id_kelas_kuliah' => $idKelasKuliah,
                'id_registrasi_mahasiswa' => $id_reg,
                'nim' => $riwayat_pendidikan->nim, 
                'id_mahasiswa' => $riwayat_pendidikan->id_mahasiswa, 
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa, 
                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi, 
                'id_prodi' => $riwayat_pendidikan->id_prodi, 
                'nama_kelas_kuliah' => $kelas_kuliah->nama_kelas_kuliah,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa, 
                'id_matkul' => $kelas_kuliah->id_matkul,
                'kode_mata_kuliah' => $kelas_kuliah->kode_mata_kuliah, 
                'nama_mata_kuliah' => $kelas_kuliah->nama_mata_kuliah, 
                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran, 
            ]);
            // dd($pesertaKelasKuliah);

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
}
