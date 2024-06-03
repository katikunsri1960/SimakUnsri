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
use App\Models\Perkuliahan\RencanaPembelajaran;

class KrsController extends Controller
{
    public function index(Request $request)
    {
    // DATA BAHAN 
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('pembimbing_akademik')
                    ->select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();
                    // dd($riwayat_pendidikan);
                    
        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();

        $total_sks_akt = 0;
        $total_sks_regular=0;
        $total_sks_merdeka=0;

        //DATA AKTIVITAS 
        $db = new MataKuliah();

        
        $data_akt = $db->getMKAktivitas($riwayat_pendidikan->id_prodi, $riwayat_pendidikan->id_kurikulum);

        list($krs_akt, $data_akt_ids, $mk_akt) = $db->getKrsAkt($id_reg, $semester_aktif);
        // dd($data_akt_ids);     

        // PK GUNAKAN SMETER 
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->whereRaw("RIGHT(id_semester, 1) != 3")
                    ->orderBy('id_semester', 'DESC')
                    ->first();
                    // dd($akm);

        $semester = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->orderBy('id_semester', 'DESC')
                    ->get();

        $sks_max = $db->getSksMax($id_reg, $semester_aktif);
        // dd($sks_max);


        // PK GUNAKAN SMETER 
        $status_mahasiswa = AktivitasKuliahMahasiswa::select('id_status_mahasiswa')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    ->orderBy('id_semester', 'DESC')
                    ->pluck('id_status_mahasiswa')
                    ->first();

            if ($status_mahasiswa !== null) {
                $data_status_mahasiswa = $status_mahasiswa;
            } else {
                $data_status_mahasiswa = 'X';
            }
            // dd($data_status_mahasiswa);

        // PK GUNAKAN SMETER 
        $semester_ke = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->whereRaw("RIGHT(id_semester, 1) != 3")->count();

        $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $semester_aktif, $data_akt_ids);
        
        $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_aktif);



    // DATA MK_MERDEKA
        $fakultas=Fakultas::all();

        $selectedFakultasId = $request->input('fakultas_id');

        $prodi = ProgramStudi::where('fakultas_id', $selectedFakultasId)->get();

        $mk_merdeka = $db->getMKMerdeka($prodi, $semester_aktif);
        

       // MATAKULIAH TANPA GANJIL GENAP
       $mk_regular = $db->getMKRegular($riwayat_pendidikan, $data_akt_ids, $semester_aktif);

    // RPS
        // $rps= $db->with('rencana_pembelajaran')
        // ->whereHas('rencana_pembelajaran', function($query){
        //     $query->whereNotNull('id_matkul');
        // })
        // // ->limit(10)
        // ->where('id_prodi',  $riwayat_pendidikan->id_prodi)
        // ->get();
        // dd($rps);
        $id_matkul_rps= $request;

        $rps = RencanaPembelajaran::where('id_matkul', 'e960ace5-48ae-4c86-8c0b-e7e75d9d25cd')->get();

    // TOTAL SELURUH SKS
        $total_sks_akt = $krs_akt->sum('aktivitas_mahasiswa.konversi.sks_mata_kuliah');
        $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
        $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

        $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt;

        return view('mahasiswa.krs.index',[
            'formatDosenPengajar' => function($dosenPengajar) {
                return $this->formatDosenPengajar($dosenPengajar);
            }], compact(
            'riwayat_pendidikan',
            'semester_aktif',
            'krs_regular',
            'krs_merdeka',
            'total_sks_merdeka',
            'total_sks_regular',
            'akm', 'sks_max', 'semester',
            'total_sks',
            'status_mahasiswa',
            'data_status_mahasiswa',
            'semester_ke',
            'fakultas', 'prodi',
            'krs_akt','data_akt', 'mk_akt',
            'total_sks_akt',
            'mk_merdeka',
            'mk_regular', 'rps'
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

            $semester_aktif = SemesterAktif::leftJoin('semesters', 'semesters.id_semester', 'semester_aktifs.id_semester')
                    ->first();

            $db = new MataKuliah();

            list($krs_akt, $data_akt_ids) = $db->getKrsAkt($id_reg, $semester_aktif);
            
            $sks_max = $db->getSksMax($id_reg, $semester_aktif);
            
            $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $semester_aktif, $data_akt_ids);
            
            $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_aktif);

            $total_sks_akt = $krs_akt->sum('aktivitas_mahasiswa.konversi.sks_mata_kuliah');
            $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
            $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');
    
            $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt;

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

    public function hapus_kelas_kuliah(PesertaKelasKuliah $pesertaKelas)
    {
        $pesertaKelas->delete();

        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }

    

}
