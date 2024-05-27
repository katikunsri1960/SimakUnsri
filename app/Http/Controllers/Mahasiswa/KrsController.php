<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Fakultas;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KelasKuliah;
use Illuminate\Cache\RateLimiting\Limit;
use App\Models\Perkuliahan\MatkulMerdeka;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class KrsController extends Controller
{
    public function krs(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    // ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa' )
                    ->first();
                    // dd($riwayat_pendidikan->id_kurikulum);

        $prodi_id = $riwayat_pendidikan->id_prodi;

        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();

        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();

        // DATA MK_MERDEKA
        $fakultas=Fakultas::all();

        $selectedFakultasId = $request->input('fakultas_id');

        $prodi = ProgramStudi::where('fakultas_id', $selectedFakultasId)->get();

        //DATA AKTIVITAS 
        $db = new MataKuliah();

        $data_akt = $db->getMKAktivitas($prodi_id);

        // Ekstrak sub-array 'data' dari $data_akt
        $data_akt_data = $data_akt['data']['data'];

        // Ekstrak nilai 'id_matkul' dari sub-array 'data'
        $data_akt_ids = array_column($data_akt_data, 'id_matkul');

        // Ambil data KRS untuk nilai 'id_matkul' yang diperoleh
        $krs_akt = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*', 'peserta_kelas_kuliahs.id_prodi', 'mata_kuliahs.sks_mata_kuliah')
            ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
            ->whereIn('peserta_kelas_kuliahs.id_matkul', $data_akt_ids)
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->get();

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

        $total_sks_regular=0;
        $total_sks_merdeka=0;
        
        $krs_merdeka = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'mata_kuliahs.sks_mata_kuliah')
                ->join('matkul_merdekas', 'matkul_merdekas.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->leftJoin('kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', '=', 'peserta_kelas_kuliahs.id_kelas_kuliah')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->get();

            $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');

        $krs_regular = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'mata_kuliahs.sks_mata_kuliah')
                ->leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->where('kelas_kuliahs.id_prodi', $prodi_id)
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->where('id_semester', $semester_aktif->id_semester)
                ->get();

            // return response()->json(['isEnrolled_merdeka' => $krs_regular]);

            $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

        $total_sks = $total_sks_regular + $total_sks_merdeka;
        // dd($krs_merdeka);

        $mk_merdeka = MatkulMerdeka::leftJoin('mata_kuliahs', 'matkul_merdekas.id_matkul', '=', 'mata_kuliahs.id_matkul')
                ->leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                ->select('mata_kuliahs.id_matkul', 'mata_kuliahs.kode_mata_kuliah', 'mata_kuliahs.nama_mata_kuliah', 'matkul_kurikulums.semester', 'matkul_kurikulums.sks_mata_kuliah')
                ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                ->orderBy('jumlah_kelas_kuliah', 'DESC')
                ->orderBy('matkul_kurikulums.semester')
                ->whereIn('mata_kuliahs.id_prodi', $prodi->pluck('id')) // Hanya mengambil mata kuliah yang termasuk dalam program studi yang dipilih
                ->orderBy('matkul_kurikulums.sks_mata_kuliah')
                ->get();

       // MATAKULIAH TANPA GANJIL GENAP
        $matakuliah = MataKuliah::leftJoin('matkul_kurikulums','matkul_kurikulums.id_matkul','mata_kuliahs.id_matkul')
                    ->leftJoin('list_kurikulums', 'list_kurikulums.id_kurikulum', '=', 'matkul_kurikulums.id_kurikulum')
                    ->leftJoin('kelas_kuliahs', 'kelas_kuliahs.id_matkul', '=', 'mata_kuliahs.id_matkul')
                    ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','mata_kuliahs.sks_mata_kuliah', 'kelas_kuliahs.id_prodi as id_prodi_kelas' , 'list_kurikulums.nama_kurikulum', 'is_active')            
                    ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                    // ->where('kelas_kuliahs.id_prodi', $prodi_id)
                    ->where('mata_kuliahs.id_prodi', $prodi_id)
                    ->where('matkul_kurikulums.id_kurikulum', $riwayat_pendidikan->id_kurikulum)
                    // ->where('list_kurikulums.is_active', '1')
                    // ->where('list_kurikulums.id_kurikulum', $riwayat_pendidikan->id_kurikulum)
                    // ->whereIn('mata_kuliahs.kode_mata_kuliah', ['UNI1001','UNI1002','UNI1003','UNI1004'])
                    ->whereNotIn('mata_kuliahs.id_matkul', $data_akt_ids)
                    ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','mata_kuliahs.sks_mata_kuliah', 'kelas_kuliahs.id_prodi', 'list_kurikulums.nama_kurikulum', 'is_active')
                    ->orderBy('jumlah_kelas_kuliah', 'DESC')
                    ->orderBy('matkul_kurikulums.semester')
                    ->orderBy('matkul_kurikulums.sks_mata_kuliah')
                    // ->limit(10)
                    ->get();
                    // dd($matakuliah);

        return view('mahasiswa.krs.index', compact(
            'riwayat_pendidikan',
            'semester_aktif',
            'krs_regular',
            'krs_merdeka',
            'total_sks_merdeka',
            'total_sks_regular',
            'akm', 'sks_max',
            'total_sks',
            'status_mahasiswa',
            'data_status_mahasiswa',
            'semester_ke',
            'fakultas', 'prodi',
            'krs_akt','data_akt', 'data_akt_data',
            'mk_merdeka',
            'matakuliah',
        ));
    }
    public function pilih_prodi(Request $request)
    {
        $fakultasId = $request->input('id');

        $prodi = ProgramStudi::where('fakultas_id', $fakultasId)->get();

        return response()->json(['prodi' => $prodi]);
    }

    public function pilihMataKuliahMerdeka(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::leftJoin('semesters', 'semesters.id_semester', 'semester_aktifs.id_semester')
                ->first();
        // Ambil id_prodi dari request
        $idProdi = $request->input('id_prodi');
        
        // Query untuk mengambil data mata kuliah merdeka berdasarkan id_prodi yang dipilih
        $mkMerdeka = MatkulMerdeka::leftJoin('mata_kuliahs', 'matkul_merdekas.id_matkul', '=', 'mata_kuliahs.id_matkul')
            ->leftJoin('matkul_kurikulums', 'matkul_kurikulums.id_matkul', '=', 'mata_kuliahs.id_matkul')
            ->select('mata_kuliahs.id_matkul', 'mata_kuliahs.kode_mata_kuliah', 'mata_kuliahs.nama_mata_kuliah', 'matkul_kurikulums.semester', 'matkul_kurikulums.sks_mata_kuliah')
            ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul = mata_kuliahs.id_matkul and kelas_kuliahs.id_semester = '".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
            ->where('mata_kuliahs.id_prodi', $idProdi)
            ->orderBy('jumlah_kelas_kuliah', 'DESC')
            ->orderBy('matkul_kurikulums.semester')
            ->orderBy('matkul_kurikulums.sks_mata_kuliah')
            ->get();

        $krs_merdeka = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'mata_kuliahs.sks_mata_kuliah')
            ->join('matkul_merdekas', 'matkul_merdekas.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
            ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
            ->leftJoin('kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', '=', 'peserta_kelas_kuliahs.id_kelas_kuliah')
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->get();

        return response()->json(['mk_merdeka' => $mkMerdeka, 'krs_merdeka'=>$krs_merdeka]);
    }

    public function get_kelas_kuliah(Request $request)
    {
        $idMatkul = $request->get('id_matkul');

        
        $semester_aktif = SemesterAktif::select('*')
                        ->first();

        $kelasKuliah = KelasKuliah::with(['dosen_pengajar.dosen'])
                    ->withCount('peserta_kelas')
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

            $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
                    ->first();

            $prodi_id = $riwayat_pendidikan->id_prodi;

            $semester_aktif = SemesterAktif::leftJoin('semesters', 'semesters.id_semester', 'semester_aktifs.id_semester')
                    ->first();

            $ips = AktivitasKuliahMahasiswa::select('ips')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    ->orderBy('id_semester', 'DESC')
                    ->pluck('ips')
                    ->first();

            if ($ips !== null) {
                if ($ips >= 3.00) {
                    $sks_max = 24;
                } elseif ($ips >= 2.50 && $ips <= 2.99) {
                    $sks_max = 21;
                } elseif ($ips >= 2.00 && $ips <= 2.49) {
                    $sks_max = 18;
                } elseif ($ips >= 1.50 && $ips <= 1.99) {
                    $sks_max = 15;
                } elseif ($ips < 1.50) {
                    $sks_max = 12;
                } else {
                    $sks_max = "Tidak Diisi";
                }
            } else {
                $sks_max = "Tidak Diisi";
            }

            $krs_merdeka = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*', 'kelas_kuliahs.id_prodi', 'mata_kuliahs.sks_mata_kuliah')
                    ->join('matkul_merdekas', 'matkul_merdekas.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                    ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                    ->leftJoin('kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', '=', 'peserta_kelas_kuliahs.id_kelas_kuliah')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->get();

            $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');

            $krs_regular = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*', 'kelas_kuliahs.id_prodi', 'mata_kuliahs.sks_mata_kuliah')
                    ->leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                    ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                    ->where('kelas_kuliahs.id_prodi', $prodi_id)
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    ->get();

            $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

            $total_sks = $total_sks_regular + $total_sks_merdeka;

            $sks_mk = KelasKuliah::select('sks_mata_kuliah')
                    ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'kelas_kuliahs.id_matkul')
                    ->where('id_kelas_kuliah', $idKelasKuliah)
                    ->pluck('sks_mata_kuliah')
                    ->first();


            // Check if the total SKS exceeds the maximum allowed SKS
            if (($total_sks + $sks_mk) > $sks_max) {
                return response()->json(['message' => 'Total SKS tidak boleh melebihi SKS maksimum.', 'sks_max' => $sks_max], 400);
            }

            $kelas_kuliah = KelasKuliah::where('id_kelas_kuliah', $idKelasKuliah)->first();

            DB::beginTransaction();

            $pesertaKelasKuliah = PesertaKelasKuliah::create([
                'approved' => 0,
                'id_kelas_kuliah' => $idKelasKuliah,
                'id_registrasi_mahasiswa' => $id_reg,
                'nim' => $riwayat_pendidikan->nim,
                'id_mahasiswa' => $riwayat_pendidikan->id_mahasiswa,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                'id_prodi' => $riwayat_pendidikan->id_prodi,
                'nama_kelas_kuliah' => $kelas_kuliah->nama_kelas_kuliah,
                'id_matkul' => $kelas_kuliah->id_matkul,
                'kode_mata_kuliah' => $kelas_kuliah->kode_mata_kuliah,
                'nama_mata_kuliah' => $kelas_kuliah->nama_mata_kuliah,
                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
            ]);

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan', 'sks_max' => $sks_max, 'sks_mk'=>$sks_mk], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
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

    public function hapus_kelas_kuliah(PesertaKelasKuliah $pesertaKelas)
    {
        // dd($pesertaKelas);
        $pesertaKelas->delete();

        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }

    public function ambilAktivitas($id_matkul)
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
        ->where('id_registrasi_mahasiswa', $id_reg)
        ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
        ->first();

        $prodi_id = $riwayat_pendidikan->id_prodi;
        
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->whereNotIn('id_status_mahasiswa', ['N'])
                    ->orderBy('id_semester', 'DESC')
                    ->first();
        
        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
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

            $dosen_pembimbing = BiodataDosen::select('biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen', 'biodata_dosens.nidn')
                    // ->leftJoin()
                    // ->where('id_prodi', $prodi_id)
                    ->get();

        return view('mahasiswa.krs.ambil-aktivitas-mahasiswa', 
        [
            'id_matkul' => $id_matkul, 
            'akm'=>$akm, 
            'sks_max'=>$sks_max,
            'dosen_pembimbing'=>$dosen_pembimbing

        ]);
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');
        // $prodi_id = auth()->user()->fk_id;
        $tahun_ajaran = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();
        
        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran)
                                ->orderby('nama_dosen', 'asc');
        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%")
                  ->where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function simpanAktivitas(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'pembimbing1' => 'required|string|max:255',
            'pembimbing2' => 'required|string|max:255',
            'judulSkripsi' => 'required|string|max:255',
            'id_matkul' => 'required|uuid',
        ]);

        // Simpan data ke tabel peserta_kelas_kuliah
        PesertaKelasKuliah::create([
            'id_matkul' => $request->id_matkul,
            'pembimbing1' => $request->pembimbing1,
            'pembimbing2' => $request->pembimbing2,
            'judulSkripsi' => $request->judulSkripsi,
            // tambahkan field lain yang diperlukan
        ]);

        return redirect()->route('mahasiswa.krs.index')->with('success', 'Data berhasil disimpan');
    }

    
}
