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
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;

class KrsController extends Controller
{
    private function formatDosenPengajar($dosen_bimbing)
    {
        // Mengembalikan daftar dosen dalam format ul li
        $output = '<ul>';
        foreach ($dosen_bimbing as $dosen) {
            $output .= '<li>' . htmlspecialchars($dosen, ENT_QUOTES, 'UTF-8') . '</li>';
        }
        $output .= '</ul>';

        return $output;
    }

    public function krs(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    // ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa' )
                    ->first();
                    // dd($riwayat_pendidikan->id_kurikulum);

        $prodi_id = $riwayat_pendidikan->id_prodi;
        
        $id_kurikulum = $riwayat_pendidikan->id_kurikulum;
        
        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();

        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->count();

        // DATA MK_MERDEKA
        $fakultas=Fakultas::all();

        $selectedFakultasId = $request->input('fakultas_id');

        $prodi = ProgramStudi::where('fakultas_id', $selectedFakultasId)->get();

        //DATA AKTIVITAS 
        $db = new MataKuliah();

        $data_akt = $db->getMKAktivitas($prodi_id, $id_kurikulum);

        // Ekstrak sub-array 'data' dari $data_akt
        $data_akt_data = $data_akt['data']['data'];

        // Ekstrak nilai 'id_matkul' dari sub-array 'data'
        $data_akt_ids = array_column($data_akt_data, 'id_matkul');

        $total_sks_akt = 0;
        // Ambil data KRS untuk nilai 'id_matkul' yang diperoleh
        $krs_akt = AnggotaAktivitasMahasiswa::with(['aktivitas_mahasiswa.bimbing_mahasiswa'])
        ->select(
            'aktivitas_mahasiswas.id', 
            'aktivitas_mahasiswas.nama_jenis_aktivitas', 
            'aktivitas_mahasiswas.nama_jenis_anggota',
            'aktivitas_mahasiswas.nama_semester',
            'aktivitas_mahasiswas.id_prodi',
            'aktivitas_mahasiswas.lokasi',
            'aktivitas_mahasiswas.mk_konversi',
            'anggota_aktivitas_mahasiswas.id_aktivitas', 
            'anggota_aktivitas_mahasiswas.nim', 
            'anggota_aktivitas_mahasiswas.judul', 
            'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', 
            'bimbing_mahasiswas.approved',
            // 'anggota_aktivitas_mahasiswas.*','aktivitas_mahasiswas.*', 'bimbing_mahasiswas.*'
         )
            ->leftJoin('aktivitas_mahasiswas', 'aktivitas_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->leftJoin('bimbing_mahasiswas', 'bimbing_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->where('anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', $id_reg)
            ->where('aktivitas_mahasiswas.id_semester', $semester_aktif->id_semester)
            ->where('aktivitas_mahasiswas.id_prodi', $prodi_id)
            ->whereIn('aktivitas_mahasiswas.id_jenis_aktivitas', ['2', '3', '4', '22'])
            ->whereNot('bimbing_mahasiswas.id_bimbing_mahasiswa', NUll)
            // ->orderBy('nama_kelas_kuliah', 'DESC')
            // ->limit(10)
            ->groupBy(
                'aktivitas_mahasiswas.id', 
                'aktivitas_mahasiswas.nama_jenis_aktivitas', 
                'aktivitas_mahasiswas.nama_jenis_anggota',
                'aktivitas_mahasiswas.nama_semester',
                'aktivitas_mahasiswas.id_prodi',
                'aktivitas_mahasiswas.lokasi',
                'aktivitas_mahasiswas.mk_konversi',
                'anggota_aktivitas_mahasiswas.id_aktivitas', 
                'anggota_aktivitas_mahasiswas.nim', 
                'anggota_aktivitas_mahasiswas.judul', 
                'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', 
                'bimbing_mahasiswas.approved',
            )
            ->get();
        // dd($krs_akt);
        
        $total_sks_akt = $krs_akt->sum('sks_mata_kuliah');

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

        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->whereNotIn('id_status_mahasiswa', ['N'])->count();

        if($semester_ke == 1 || $semester_ke == 2 ){
            $sks_max = 20;
        }else{
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
        }

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
        
        $krs_merdeka = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'kelas_kuliahs.jadwal_hari', 'kelas_kuliahs.jadwal_jam_mulai', 'kelas_kuliahs.jadwal_jam_selesai', 'mata_kuliahs.sks_mata_kuliah')
                ->join('matkul_merdekas', 'matkul_merdekas.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->leftJoin('kelas_kuliahs', 'kelas_kuliahs.id_kelas_kuliah', '=', 'peserta_kelas_kuliahs.id_kelas_kuliah')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->get();

            $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');

        $krs_regular = PesertaKelasKuliah::select('peserta_kelas_kuliahs.*','kelas_kuliahs.id_prodi', 'kelas_kuliahs.jadwal_hari', 'kelas_kuliahs.jadwal_jam_mulai', 'kelas_kuliahs.jadwal_jam_selesai', 'mata_kuliahs.sks_mata_kuliah')
                ->leftJoin('kelas_kuliahs', 'peserta_kelas_kuliahs.id_kelas_kuliah', '=', 'kelas_kuliahs.id_kelas_kuliah')
                ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'peserta_kelas_kuliahs.id_matkul')
                ->where('kelas_kuliahs.id_prodi', $prodi_id)
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->whereNotIn('peserta_kelas_kuliahs.id_matkul', $data_akt_ids)
                ->where('id_semester', $semester_aktif->id_semester)
                ->get();

            // return response()->json(['isEnrolled_merdeka' => $krs_regular]);

            $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

        $total_sks = $total_sks_regular + $total_sks_merdeka;
        // dd($krs_regular);

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
                    ->select('*')
                    // ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','mata_kuliahs.sks_mata_kuliah', 'kelas_kuliahs.id_prodi as id_prodi_kelas' , 'list_kurikulums.nama_kurikulum', 'is_active')            
                    ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='".$semester_aktif['id_semester']."') AS jumlah_kelas_kuliah"))
                    ->where('kelas_kuliahs.id_prodi', $prodi_id)
                    // ->where('mata_kuliahs.id_prodi', $prodi_id)
                    ->where('matkul_kurikulums.id_kurikulum', $riwayat_pendidikan->id_kurikulum)
                    // ->where('list_kurikulums.is_active', '1')
                    // ->where('list_kurikulums.id_kurikulum', $riwayat_pendidikan->id_kurikulum)
                    // ->whereIn('mata_kuliahs.kode_mata_kuliah', ['UNI1001','UNI1002','UNI1003','UNI1004'])
                    ->whereNotIn('mata_kuliahs.id_matkul', $data_akt_ids)
                    // ->groupBy('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','matkul_kurikulums.semester','mata_kuliahs.sks_mata_kuliah', 'kelas_kuliahs.id_prodi', 'list_kurikulums.nama_kurikulum', 'is_active')
                    ->orderBy('jumlah_kelas_kuliah', 'DESC')
                    ->orderBy('matkul_kurikulums.semester')
                    ->orderBy('matkul_kurikulums.sks_mata_kuliah')
                    // ->limit(10)
                    ->get();
                    // dd($matakuliah);

        return view('mahasiswa.krs.index',[
            'formatDosenPengajar' => function($dosenPengajar) {
                return $this->formatDosenPengajar($dosenPengajar);
            }
        ], compact(
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
            'total_sks_akt',
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
            // dd($idKelasKuliah);
            
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
            
            $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->whereNotIn('id_status_mahasiswa', ['N'])->count();

            if($semester_ke == 1 || $semester_ke == 2 ){
                $sks_max = 20;
            }else{
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

            $kelas_mk = KelasKuliah::leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul','=','kelas_kuliahs.id_matkul')
                    ->where('id_kelas_kuliah', $idKelasKuliah)->first();
                    // return response()->json(['kelas_mk' => $kelas_mk]);

            DB::beginTransaction();

            $peserta = PesertaKelasKuliah::create([
                'approved' => 0,
                'id_kelas_kuliah' => $idKelasKuliah,
                'id_registrasi_mahasiswa' => $id_reg,
                'nim' => $riwayat_pendidikan->nim,
                'id_mahasiswa' => $riwayat_pendidikan->id_mahasiswa,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                'id_prodi' => $riwayat_pendidikan->id_prodi,
                'nama_kelas_kuliah' => $kelas_mk->nama_kelas_kuliah,
                'id_matkul' => $kelas_mk->id_matkul,
                'kode_mata_kuliah' => $kelas_mk->kode_mata_kuliah,
                'nama_mata_kuliah' => $kelas_mk->nama_mata_kuliah,
                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                // 'jadwal_hari' => $kelas_mk->jadwal_hari,
                // 'jadwal_mulai' => $kelas_mk->jadwal_mulai,
                // 'jadwal_selesai' => $kelas_mk->jadwal_selesai,
            ]);

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan', 'sks_max' => $sks_max, 'sks_mk'=>$sks_mk, '$peserta'=>$peserta], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data', 'error' => $e->getMessage()], 500);
        }
    }

    // public function updateKelasKuliah(Request $request)
    // {
    //     try {
    //         $idKelasKuliah = $request->input('id_kelas_kuliah');
    //         $id_reg = auth()->user()->fk_id;

    //         $riwayat_pendidikan = RiwayatPendidikan::with(['periode_masuk'])
    //                         ->where('id_registrasi_mahasiswa', $id_reg)
    //                         ->first();

    //         $kelas_kuliah = KelasKuliah::where('id_kelas_kuliah', $idKelasKuliah)->first();


    //         // Lakukan penyimpanan data
    //         DB::beginTransaction();

    //         // Hapus data peserta_kelas_kuliah yang memiliki id_matkul yang sama
    //         PesertaKelasKuliah::where('id_matkul', $idKelasKuliah)
    //             ->where('id_registrasi_mahasiswa', $id_reg)
    //             ->delete();

    //         // Lakukan penyimpanan baru jika belum ada
    //         PesertaKelasKuliah::create([
    //             'id_kelas_kuliah' => $request->input('id_kelas_kuliah'),
    //             'id_registrasi_mahasiswa' => $id_reg,
    //             'nim' => $riwayat_pendidikan->nim,
    //             'id_mahasiswa' => $riwayat_pendidikan->id_mahasiswa,
    //             'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
    //             'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
    //             'id_prodi' => $riwayat_pendidikan->id_prodi,
    //             'nama_kelas_kuliah' => $kelas_kuliah->nama_kelas_kuliah,
    //             'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
    //             'id_matkul' => $kelas_kuliah->id_matkul,
    //             'kode_mata_kuliah' => $kelas_kuliah->kode_mata_kuliah,
    //             'nama_mata_kuliah' => $kelas_kuliah->nama_mata_kuliah,
    //             'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
    //         ]);

    //         // Selesaikan transaksi
    //         DB::commit();

    //         // Respon sesuai kebutuhan
    //         return response()->json(['message' => 'Data berhasil di-update'], 200);
    //     } catch (\Exception $e) {
    //         // Tangani kesalahan
    //         DB::rollback();

    //         return response()->json(['message' => 'Terjadi kesalahan saat meng-update data'], 500);
    //     }
    // }

    public function hapus_kelas_kuliah(PesertaKelasKuliah $pesertaKelas)
    {
        // dd($pesertaKelas);
        $pesertaKelas->delete();

        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }

}
