<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\MatkulMerdeka;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class KrsController extends Controller
{
    public function krs()
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

        $prodi_id = $riwayat_pendidikan->id_prodi;

        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();

        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();

        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->whereNotIn('id_status_mahasiswa', ['N'])
                    ->orderBy('id_semester', 'DESC')
                    ->first();

        $ips = AktivitasKuliahMahasiswa::select('ips')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    // ->where('id_status_mahasiswa', ['O'])
                    ->orderBy('id_semester', 'DESC')
                    ->pluck('ips')->first();

            if ($ips !== null) {
                if($ips >= 3.00){
                    $sks_max = 24;
                }elseif($ips >= 2.50 && $ips <= 2.99){
                    $sks_max = 21;
                }elseif($ips >= 2.00 && $ips <= 2.49){
                    $sks_max = 18;
                }elseif($ips >= 1.50 && $ips <= 1.99){
                    $sks_max = 15;
                }elseif($ips < 1.50){
                    $sks_max = 12;
                }else{
                    $sks_max = "Tidak Diisi";
                }
            } else {
                $sks_max = "Tidak Diisi";
            }


            // dd($ips);

        $status_mahasiswa = AktivitasKuliahMahasiswa::select('id_status_mahasiswa')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    // ->where('id_status_mahasiswa', ['O'])
                    ->orderBy('id_semester', 'DESC')
                    ->pluck('id_status_mahasiswa')->first();

            if ($status_mahasiswa !== null) {
                $data_status_mahasiswa = $status_mahasiswa;
            } else {
                $data_status_mahasiswa = 'X';
            }
            // dd($data_status_mahasiswa);

        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->whereNotIn('id_status_mahasiswa', ['N'])->count();

        $pembimbing_akademik = BimbingMahasiswa::select('nama_dosen', 'id_semester')
                    ->leftJoin('kategori_kegiatans as kk', 'bimbing_mahasiswas.id_kategori_kegiatan', '=', 'kk.id_kategori_kegiatan')
                    ->leftJoin('anggota_aktivitas_mahasiswas as aam', 'bimbing_mahasiswas.id_aktivitas', '=', 'aam.id_aktivitas')
                    ->leftJoin('aktivitas_mahasiswas as am', 'bimbing_mahasiswas.id_aktivitas', '=', 'am.id_aktivitas')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_jenis_aktivitas', '7')
                    ->orderBy('id_semester','DESC')
                    ->first();


        $krs_merdeka = PesertaKelasKuliah::join('matkul_merdekas', 'peserta_kelas_kuliahs.id_matkul', '=', 'matkul_merdekas.id_matkul')
            ->leftJoin('mata_kuliahs', 'peserta_kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
            ->leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->get();

            $total_sks_merdeka = PesertaKelasKuliah::join('matkul_merdekas', 'peserta_kelas_kuliahs.id_matkul', '=', 'matkul_merdekas.id_matkul')
                ->leftJoin('mata_kuliahs', 'peserta_kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
                ->leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->sum('mata_kuliahs.sks_mata_kuliah');

        $krs_regular = PesertaKelasKuliah::leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
            ->leftJoin('mata_kuliahs', 'peserta_kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->where('id_semester', $semester_aktif->id_semester)
            ->get();

            $total_sks_regular = PesertaKelasKuliah::leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                ->leftJoin('mata_kuliahs', 'peserta_kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->where('id_semester', $semester_aktif->id_semester)
                ->sum('mata_kuliahs.sks_mata_kuliah');

        $total_sks = $total_sks_regular + $total_sks_merdeka;
        // dd($total_sks);


        $mk_merdeka = MatkulMerdeka::leftJoin('mata_kuliahs', 'matkul_merdekas.id_matkul', '=', 'mata_kuliahs.id_matkul')
                    ->leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                    ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                    ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                    ->get();

       $data_univ = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                    ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                    ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.kode_mata_kuliah=mata_kuliahs.kode_mata_kuliah and kelas_kuliahs.id_prodi='".$prodi_id."' and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                    ->whereIn('mata_kuliahs.kode_mata_kuliah', array('UNI1001','UNI1002','UNI1003','UNI1004'))
                    ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                    ->get();

        // MATAKULIAH TANPA GANJIL GENAP
        $matakuliah = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                            ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                            ->where('mata_kuliahs.id_prodi', $prodi_id)
                            ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
                            ->orderBy('jumlah_kelas_kuliah', 'DESC')
                            ->orderBy('matkul_kurikulums.semester')
                            ->orderBy('matkul_kurikulums.sks_mata_kuliah')
                            ->get();


        // MATAKULIAH GANJIL GENAP
        // if(substr($semester_aktif['id_semester'],-1) == '1'){
        //     $matakuliah = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
        //                     ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
        //                     ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
        //                     ->where('mata_kuliahs.id_prodi', $prodi_id)
        //                     ->where(DB::raw("matkul_kurikulums.semester % 2"),'!=',0)
        //                     ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
        //                     ->orderBy('jumlah_kelas_kuliah', 'DESC')
        //                     ->orderBy('matkul_kurikulums.semester')
        //                     ->orderBy('matkul_kurikulums.sks_mata_kuliah')
        //                     ->get();
        // }else if(substr($semester_aktif['id_semester'],-1) == '2'){
        //     $matakuliah = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
        //                     ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
        //                     ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
        //                     ->where('mata_kuliahs.id_prodi', $prodi_id)
        //                     ->where(DB::raw("matkul_kurikulums.semester % 2"),'=',0)
        //                     ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
        //                     ->orderBy('jumlah_kelas_kuliah', 'DESC')
        //                     ->orderBy('matkul_kurikulums.semester')
        //                     ->orderBy('matkul_kurikulums.sks_mata_kuliah')
        //                     ->get();
        // }else if(substr($semester_aktif['id_semester'],-1) == '3'){
        //     $matakuliah = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
        //                     ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
        //                     ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
        //                     ->where('mata_kuliahs.id_prodi', $prodi_id)
        //                     ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','matkul_kurikulums.sks_mata_kuliah')
        //                     ->orderBy('jumlah_kelas_kuliah', 'DESC')
        //                     ->orderBy('matkul_kurikulums.semester')
        //                     ->orderBy('matkul_kurikulums.sks_mata_kuliah')
        //                     ->get();
        // }else{
        //     return redirect()->back()->with('error', 'Semester tidak terdata');
        // }




        return view('mahasiswa.krs.index', compact(
            'matakuliah',
            'pembimbing_akademik',
            'semester_aktif',
            'krs_regular',
            'akm', 'sks_max',
            'total_sks',
            'status_mahasiswa',
            'data_status_mahasiswa',
            'semester_ke',
            'mk_merdeka',
            'krs_merdeka',
        ));
    }

    public function get_kelas_kuliah(Request $request)
    {
        $idMatkul = $request->get('id_matkul');

        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::select('*',DB::raw('LEFT(id_semester, 4) as id_tahun_ajaran'), DB::raw('RIGHT(id_semester, 1) as kode_semester'))//Ambiil Nilai paling belakang id_semester untuk penentu ganjil genap
                        ->first();

        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)
                        ->first();

        $kelasKuliah = KelasKuliah::with(['dosen_pengajar.dosen'])
                    ->withCount('peserta_kelas')
                    ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                    ->where('id_semester',  $semester_aktif->id_semester) 
                    ->where('id_matkul', $idMatkul)
                    ->orderBy('nama_kelas_kuliah')
                    ->get();

        foreach ($kelasKuliah as $kelas) {
            $kelas->is_kelas_ambil = $this->cekApakahKelasSudahDiambil($request->user()->id, $kelas->id_matkul);
        }

        return response()->json($kelasKuliah);
    }

    private function cekApakahKelasSudahDiambil($id_registrasi_mahasiswa, $id_matkul)
    {
        $kelasDiambil = PesertaKelasKuliah::where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
            ->where('id_matkul', $id_matkul)
            ->exists();

        return $kelasDiambil;
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

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
    }


    public function updateKelasKuliah(Request $request)
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

            // Hapus data peserta_kelas_kuliah yang memiliki id_matkul yang sama
            PesertaKelasKuliah::where('id_matkul', $idKelasKuliah)
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->delete();

            // Lakukan penyimpanan baru jika belum ada
            PesertaKelasKuliah::create([
                'id_kelas_kuliah' => $request->input('id_kelas_kuliah'),
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


            // Selesaikan transaksi
            DB::commit();

            // Respon sesuai kebutuhan
            return response()->json(['message' => 'Data berhasil di-update'], 200);
        } catch (\Exception $e) {
            // Tangani kesalahan
            DB::rollback();

            return response()->json(['message' => 'Terjadi kesalahan saat meng-update data'], 500);
        }
    }

    public function hapus_kelas_kuliah(Request $request)
    {
        try {
            $idReg = auth()->user()->fk_id;

            $pesertaKelas = PesertaKelasKuliah::where('id_kelas_kuliah', $request->id_kelas_kuliah)
                ->where('id_registrasi_mahasiswa', $idReg)
                ->firstOrFail();

            $pesertaKelas->delete();

            return redirect()->route('mahasiswa.krs')->with('success', 'Mata kuliah berhasil dihapus dari KRS.');
        } catch (\Exception $e) {
            return redirect()->route('mahasiswa.krs')->with('error', 'Gagal menghapus mata kuliah dari KRS.');
        }
    }



}
