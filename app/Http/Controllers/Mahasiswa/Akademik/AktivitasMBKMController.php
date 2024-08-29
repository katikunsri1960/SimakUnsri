<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Mahasiswa\AktivitasMagang;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;

class AktivitasMBKMController extends Controller
{
    public function view()
    {
        // $id_reg = auth()->user()->fk_id;

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.index');
    }

    public function index_non_pertukaran()
    {
        $id_reg = auth()->user()->fk_id;
        
        // $data = AktivitasMagang::where('id_registrasi_mahasiswa', $id_reg)->get();
        // $anggota_aktivitas = AnggotaAktivitasMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->get();

        $semester_aktif = SemesterAktif::first();
        
        $data = AktivitasMahasiswa::with(['anggota_aktivitas', 'bimbing_mahasiswa', 'semester'])
                ->whereHas('anggota_aktivitas' , function($query) use ($id_reg) {
                    $query->where('id_registrasi_mahasiswa', $id_reg);
                })
                ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20'])
                ->orderBy('id_semester', 'DESC')
                ->get();

        $jumlah_data=$data->first();
                // dd($jumlah_data);
                // dd($data);

        
        $today = Carbon::now()->toDateString();

        if($today >= $semester_aktif->krs_mulai && $today <= $semester_aktif->krs_selesai ){
            $batas_isi_krs =  Carbon::parse($semester_aktif->krs_selesai)->toDateString();
        }
        elseif(($today >= $semester_aktif->tanggal_mulai_kprs && $today <= $semester_aktif->tanggal_akhir_kprs )){
            $batas_isi_krs =  Carbon::parse($semester_aktif->tanggal_akhir_kprs)->toDateString();
        }else
        {
            $batas_isi_krs =  NULL;
        }

        // dd($batas_isi_krs);

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.non-pertukaran.index', ['data' => $data, 'jumlah_data' =>$jumlah_data,'semester_aktif' => $semester_aktif,'today'=>$today ,'batas_isi_krs'=>$batas_isi_krs ]);
    }

    public function tambah()
    {
        $id_reg = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first();
        $today = Carbon::now()->toDateString();

        if($today >= $semester_aktif->krs_mulai && $today <= $semester_aktif->krs_selesai ){
            $batas_isi_krs =  Carbon::parse($semester_aktif->krs_selesai)->toDateString();
        }
        elseif(($today >= $semester_aktif->tanggal_mulai_kprs && $today <= $semester_aktif->tanggal_akhir_kprs )){
            $batas_isi_krs =  Carbon::parse($semester_aktif->tanggal_akhir_kprs)->toDateString();
        }else
        {
            $batas_isi_krs =  NULL;
        }

        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

        $aktivitas_mbkm = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                        ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20'])
                        ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                        ->get();

        $data_aktivitas_mbkm = AktivitasMahasiswa::with(['anggota_aktivitas'])
                        ->whereHas('anggota_aktivitas' , function($query) use ($id_reg) {
                                $query->where('id_registrasi_mahasiswa', $id_reg);
                        })
                        ->where('id_semester', $semester_aktif->id_semester)
                        ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20'])
                        ->get();
                        
        $sks_aktivitas_mbkm = [
                                "10", 
                                "20"
                            ];
        // dd($data_aktivitas_mbkm);

        $jumlah_aktivitas_mbkm=$data_aktivitas_mbkm->count();

        if ($today > $batas_isi_krs) {
            return redirect()->back()->with('error', 'Masa Pengajuan Aktivitas MBKM telah berakhir.');
        }elseif ($jumlah_aktivitas_mbkm > 0) {
            return redirect()->back()->with('error', 'Anda telah mengajukan Aktivitas'.' '.$aktivitas_mbkm->first()->nama_jenis_aktivitas);
        }
        // dd($jumlah_aktivitas_mbkm);

        // Pengecekan apakah KRS sudah diApprove 
        $approved_krs = PesertaKelasKuliah::where('id_registrasi_mahasiswa', $id_reg)
                ->whereHas('kelas_kuliah', function($query) use ($semester_aktif) {
                    $query ->where('id_semester', $semester_aktif->id_semester);
                })
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->where('nama_kelas_kuliah', 'LIKE', '241%' )
                ->where('approved', 1)
                ->count();
                // dd($approved);

        $approved_akt = AktivitasMahasiswa::with(['anggota_aktivitas'])
                ->whereHas('anggota_aktivitas', function($query) use ($id_reg) {
                    $query ->where('id_registrasi_mahasiswa', $id_reg);
                })
                ->where('id_semester', $semester_aktif->id_semester )
                ->where('approve_krs', 1)
                ->count();
                // dd($approved);
            
        if ( $approved_krs > 0 || $approved_akt > 0) {
        // return response()->json(['message' => 'Anda tidak bisa mengambil Mata Kuliah / Aktivitas, KRS anda telah disetujui Pembimbing Akademik.'], 400);
        return redirect()->back()->with('error', 'Anda tidak bisa mengambil Mata Kuliah / Aktivitas, KRS anda telah disetujui Pembimbing Akademik.');
        }

        $dosen_pembimbing = BiodataDosen::select('biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen', 'biodata_dosens.nidn')
                    // ->leftJoin()
                    // ->where('id_prodi', $prodi_id)
                    ->first();

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.non-pertukaran.store', [
            'data' => $data, 
            'dosen_bimbing_aktivitas'=>$dosen_pembimbing, 
            'aktivitas_mbkm'=>$aktivitas_mbkm,
            'sks_aktivitas_mbkm'=>$sks_aktivitas_mbkm
        ]);
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');
        // $prodi_id = auth()->user()->fk_id;
        $tahun_ajaran = SemesterAktif::with('semester')->first();

        // $tahun_ajaran = Semester::where('id_semester','=','20231')->where('a_periode_aktif','=','1')->get();

        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1)
                                ->orderby('nama_dosen', 'asc')->limit(10);

        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%")
                  ->where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1);
        }

        $data = $query->get();
        // dd($data);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aktivitas_mbkm' => 'required',
            'sks_mbkm' => 'required',
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|max:100', 
            'lokasi' => 'required|string|max:255',
            'dosen_bimbing_aktivitas' => 'required'
        ]);

        $id_reg = auth()->user()->fk_id;
        $aktivitas_mbkm = $request->aktivitas_mbkm;

        $semester_aktif = SemesterAktif::first();
        $krs_aktivitas_mbkm = AktivitasMahasiswa::with(['anggota_aktivitas'])
                        ->whereHas('anggota_aktivitas' , function($query) use ($id_reg) {
                                $query->where('id_registrasi_mahasiswa', $id_reg);
                        })
                        ->where('id_semester', $semester_aktif->id_semester)
                        ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20', '21'])
                        ->get();
         
        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
                    ->first();
        
        $db = new MataKuliah();
        $db_akt = new AktivitasMahasiswa();

        list($krs_akt, $data_akt_ids) = $db_akt->getKrsAkt($id_reg, $semester_aktif->id_semester);

        $sks_max = $db->getSksMax($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_periode_masuk);
        $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $semester_aktif->id_semester, $data_akt_ids);
        $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_prodi);

        $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
        $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
        $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');
        
        if(!empty($krs_aktivitas_mbkm->first())){
            $sks_akt_mbkm = $krs_aktivitas_mbkm->first()-> sks_aktivitas;
        }else{
            $sks_akt_mbkm =0;
        }

        $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt + $sks_akt_mbkm ;     

        if (($total_sks + $request->sks_mbkm) > $sks_max) {
            return redirect()->back()->with('error', 'Total SKS tidak boleh melebihi SKS maksimum. Anda sudah Mengambil'.' '.$total_sks.' SKS'.', Aktivitas MBKM yang Anda ambil memiliki SKS Konversi '.$sks_akt_mbkm.' SKS');
        }

        // dd($total_sks, $request->sks_mbkm);
        

        // Lanjutkan proses penyimpanan jika tidak ada aktivitas yang sama
        try {
            // Gunakan transaksi untuk memastikan semua operasi database berhasil
            DB::transaction(function () use ($request, $id_reg, $aktivitas_mbkm) {
            $id_reg = auth()->user()->fk_id;

            $riwayat_pendidikan = RiwayatPendidikan::with('prodi')            
                            ->where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

            $semester_aktif = SemesterAktif::with('semester')->first();
            
            $now = Carbon::now();

            $id_aktivitas = Uuid::uuid4()->toString();

            $aktivitas_mbkm = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                            ->where('id_jenis_aktivitas', $request->aktivitas_mbkm)
                            ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                            ->first();
                            // dd($aktivitas_mbkm);

                if($aktivitas_mbkm->id_jenis_aktivitas == 13 ||
                    $aktivitas_mbkm->id_jenis_aktivitas == 14 ||
                    $aktivitas_mbkm->id_jenis_aktivitas == 17||
                    $aktivitas_mbkm->id_jenis_aktivitas == 18){

                        $program_mbkm=1;
                        $nama_program_mbkm='Flagship';
                }else{
                        $program_mbkm=0;
                        $nama_program_mbkm='Mandiri';
                }

            // Simpan data ke tabel aktivitas_mahasiswas
                $aktivitas=AktivitasMahasiswa::create([
                    'approve_krs' =>0,
                    'approve_sidang' =>0,
                    'feeder'=>0,
                    'id_aktivitas' => $id_aktivitas,
                    'program_mbkm'=>$program_mbkm,
                    'nama_program_mbkm'=>$nama_program_mbkm,
                    'jenis_anggota'=>0,
                    'nama_jenis_anggota'=>'Personal',//tanyakan dirapat
                    'id_jenis_aktivitas'=>$aktivitas_mbkm->id_jenis_aktivitas,
                    'nama_jenis_aktivitas'=>$aktivitas_mbkm->nama_jenis_aktivitas,
                    'id_prodi' => $riwayat_pendidikan->id_prodi,
                    'nama_prodi'=>$riwayat_pendidikan->nama_program_studi,
                    'id_semester' => $semester_aktif->id_semester,
                    'nama_semester'=>$semester_aktif->semester->nama_semester,
                    'judul' => $request->judul,
                    'keterangan'=>$request->keterangan,
                    'lokasi'=>$request->lokasi,
                    'sk_tugas'=>Null,
                    'sumber_data'=>1,
                    'tanggal_sk_tugas'=>Null,
                    'tanggal_mulai'=>$now,//tambahkan kondisi jika aktivitas melanjutkan semester
                    'tanggal_selesai'=>Null,
                    'untuk_kampus_merdeka'=>1,
                    'asal_data'=>9,
                    'nm_asaldata'=>NULL,
                    'status_sync'=>'belum sync',
                    'mk_konversi'=>NULL,
                    'sks_aktivitas'=>$request->sks_mbkm,
                    // tambahkan field lain yang diperlukan
                ]);


                $id_anggota = Uuid::uuid4()->toString();

                // Simpan data ke tabel anggota_aktivitas_mahasiswas
                AnggotaAktivitasMahasiswa::create([
                    'feeder'=>0,
                    'id_anggota'=>$id_anggota,
                    'id_aktivitas' => $aktivitas->id_aktivitas,
                    'judul' => $aktivitas->judul,
                    'id_registrasi_mahasiswa'=>$id_reg,
                    'nim'=>$riwayat_pendidikan->nim,
                    'nama_mahasiswa'=> $riwayat_pendidikan->nama_mahasiswa,
                    'jenis_peran'=>2,
                    'nama_jenis_peran'=>'Anggota',
                    'status_sync'=>'belum sync',
                ]);   

                // if()
                
                // Periksa jumlah dosen pembimbing yang dipilih
                if (count((array)$request->dosen_bimbing_aktivitas) == 0) {
                    return redirect()->back()->with('error', 'Harap pilih minimal satu dosen pembimbing.');
                }

                //Generate id aktivitas mengajar
                $id_bimbing_mahasiswa = Uuid::uuid4()->toString();
                
                $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)
                                ->where('id_registrasi_dosen', $request->dosen_bimbing_aktivitas)->first();

                BimbingMahasiswa::create([
                    'feeder'=>0,
                    'approved'=>0,
                    'approved_dosen'=>0,
                    'id_bimbing_mahasiswa'=> $id_bimbing_mahasiswa,
                    'id_aktivitas'=>$aktivitas->id_aktivitas,
                    'judul'=>$aktivitas->judul,
                    'id_kategori_kegiatan'=>110300,
                    'nama_kategori_kegiatan'=>'Membimbing Kuliah Kerja Nyata, Praktek Kerja Nyata, Praktek Kerja Lapangan, termasuk membimbing pelatihan militer mahasiswa, pertukaran mahasiswa,  Magang, kuliah berbasis penelitian, wirausaha, dan bentuk lain pengabdian kepada masyarakat, dan sejenisnya',
                    'id_dosen'=>$dosen_pembimbing->id_dosen, 
                    'nidn'=>$dosen_pembimbing->nidn,
                    'nama_dosen'=>$dosen_pembimbing->nama_dosen,
                    'pembimbing_ke'=>1,
                    'status_sync'=>'belum sync',
                ]);
                    
                // $bimbing_urut=$bimbing->orderBy('pembimbing_ke', 'ASC')->get();
                // dd($bimbing);
                
            });

            return redirect()->route('mahasiswa.perkuliahan.mbkm.non-pertukaran')->with('success', 'Data aktivitas mahasiswa berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function hapusAktivitas($id)
    {
        $id_aktivitas=AktivitasMahasiswa::where('id', $id)->pluck('id_aktivitas');
        // dd($id_aktivitas);
        DB::beginTransaction();

        try {

            $semester_aktif = SemesterAktif::first();
            $today = Carbon::now()->toDateString();

            if($today >= $semester_aktif->krs_mulai && $today <= $semester_aktif->krs_selesai ){
                $batas_isi_krs =  Carbon::parse($semester_aktif->krs_selesai)->toDateString();
            }
            elseif(($today >= $semester_aktif->tanggal_mulai_kprs && $today <= $semester_aktif->tanggal_akhir_kprs )){
                $batas_isi_krs =  Carbon::parse($semester_aktif->tanggal_akhir_kprs)->toDateString();
            }else
            {
                $batas_isi_krs =  NULL;
            }

            if ($today > $batas_isi_krs) {
                return redirect()->back()->with('error', 'Periode Pengajuan Aktivitas MBKM telah berakhir, Data tidak dapat dihapus.');
            }

            // Menghapus bimbingan mahasiswa
            $bimbing = BimbingMahasiswa::where('id_aktivitas', $id_aktivitas);
            if ($bimbing->first()->approved==1) {
                return redirect()->back()->with('error', 'Anda tidak dapat menghapus Aktivitas MBKM ini, Aktivitas MBKM telah disetujui oleh KoProdi');
            }

            if ($bimbing) {
                $bimbing->delete();
            }
            
            // Menghapus anggota aktivitas mahasiswa
            $anggota = AnggotaAktivitasMahasiswa::where('id_aktivitas', $id_aktivitas);
            if ($anggota) {
                $anggota->delete();
            }
            
            // Menghapus aktivitas mahasiswa
            $aktivitas = AktivitasMahasiswa::findOrFail($id);
            $aktivitas->delete();

            DB::commit();

            return redirect()->route('mahasiswa.perkuliahan.mbkm.non-pertukaran')->with('success', 'Aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('mahasiswa.perkuliahan.mbkm.non-pertukaran')->with('error', $e->getMessage());
        }
    }







    //MBKM PERTUKARAN
    public function index_pertukaran()
    {
        $id_reg = auth()->user()->fk_id;
        
        // $data = AktivitasMagang::where('id_registrasi_mahasiswa', $id_reg)->get();
        // $anggota_aktivitas = AnggotaAktivitasMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->get();

        $semester_aktif = SemesterAktif::with(['semester'])->first();
        
        $data = AktivitasMahasiswa::with(['anggota_aktivitas', 'bimbing_mahasiswa', 'semester'])
                ->whereHas('anggota_aktivitas' , function($query) use ($id_reg) {
                    $query->where('id_registrasi_mahasiswa', $id_reg);
                })
                ->whereIn('id_jenis_aktivitas',['21'])
                ->orderBy('id_semester', 'DESC')
                ->get();
                
        $jumlah_data=$data->first();
        // dd($jumlah_data);

        $today = Carbon::now()->toDateString();

        if($today >= $semester_aktif->krs_mulai && $today <= $semester_aktif->krs_selesai ){
            $batas_isi_krs =  Carbon::parse($semester_aktif->krs_selesai)->toDateString();
        }
        elseif(($today >= $semester_aktif->tanggal_mulai_kprs && $today <= $semester_aktif->tanggal_akhir_kprs )){
            $batas_isi_krs =  Carbon::parse($semester_aktif->tanggal_akhir_kprs)->toDateString();
        }else
        {
            $batas_isi_krs =  NULL;
        }

        // dd($data);

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.pertukaran.index', ['data' => $data, 'jumlah_data' =>$jumlah_data,'semester_aktif' => $semester_aktif, 'today'=>$today ,'batas_isi_krs'=>$batas_isi_krs ]);
    }

    public function tambah_pertukaran()
    {
        $id_reg = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first();
        $today = Carbon::now()->toDateString();

        if($today >= $semester_aktif->krs_mulai && $today <= $semester_aktif->krs_selesai ){
            $batas_isi_krs =  Carbon::parse($semester_aktif->krs_selesai)->toDateString();
        }
        elseif(($today >= $semester_aktif->tanggal_mulai_kprs && $today <= $semester_aktif->tanggal_akhir_kprs )){
            $batas_isi_krs =  Carbon::parse($semester_aktif->tanggal_akhir_kprs)->toDateString();
        }else
        {
            $batas_isi_krs =  NULL;
        }

        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

        $aktivitas_mbkm = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                        ->whereIn('id_jenis_aktivitas',['21'])
                        ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                        ->get();

        $data_aktivitas_mbkm = AktivitasMahasiswa::with(['anggota_aktivitas'])
                        ->whereHas('anggota_aktivitas' , function($query) use ($id_reg) {
                                $query->where('id_registrasi_mahasiswa', $id_reg);
                        })
                        ->where('id_semester', $semester_aktif->id_semester)
                        ->whereIn('id_jenis_aktivitas',['21'])
                        ->get();
                        
        $sks_aktivitas_mbkm = [
                                "10", 
                                "20"
                            ];

        // $dosen_pembimbing = BiodataDosen::select('biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen', 'biodata_dosens.nidn')
        //             // ->leftJoin()
        //             ->where('id_dosen', $data->dosen_pa)
        //             ->first();

        // dd($dosen_pembimbing);

        $jumlah_aktivitas_mbkm=$data_aktivitas_mbkm->count();

        if ($today > $batas_isi_krs) {
            return redirect()->back()->with('error', 'Masa Pengajuan Aktivitas MBKM telah berakhir.');
        }elseif ($jumlah_aktivitas_mbkm > 0) {
            return redirect()->back()->with('error', 'Anda telah mengajukan Aktivitas'.' '.$aktivitas_mbkm->first()->nama_jenis_aktivitas);
        }

        $approved_krs = PesertaKelasKuliah::where('id_registrasi_mahasiswa', $id_reg)
                ->whereHas('kelas_kuliah', function($query) use ($semester_aktif) {
                    $query ->where('id_semester', $semester_aktif->id_semester);
                })
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->where('nama_kelas_kuliah', 'LIKE', '241%' )
                ->where('approved', 1)
                ->count();
                // dd($approved);

        $approved_akt = AktivitasMahasiswa::with(['anggota_aktivitas'])
                ->whereHas('anggota_aktivitas', function($query) use ($id_reg) {
                    $query ->where('id_registrasi_mahasiswa', $id_reg);
                })
                ->where('id_semester', $semester_aktif->id_semester )
                ->where('approve_krs', 1)
                ->count();
                // dd($approved);
            
        if ( $approved_krs > 0 || $approved_akt > 0) {
        // return response()->json(['message' => 'Anda tidak bisa mengambil Mata Kuliah / Aktivitas, KRS anda telah disetujui Pembimbing Akademik.'], 400);
        return redirect()->back()->with('error', 'Anda tidak bisa mengambil Mata Kuliah / Aktivitas, KRS anda telah disetujui Pembimbing Akademik.');
        }

        // dd($jumlah_aktivitas_mbkm);



        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.pertukaran.store', [
            'data' => $data, 
            'aktivitas_mbkm'=>$aktivitas_mbkm,
            'sks_aktivitas_mbkm'=>$sks_aktivitas_mbkm,
            // 'dosen_pembimbing'=>$dosen_pembimbing
        ]);
    }

    public function store_pertukaran(Request $request)
    {
        $validated = $request->validate([
            'aktivitas_mbkm' => 'required',
            'sks_mbkm' => 'required',
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|max:100', 
            'lokasi' => 'required|string|max:255'
        ]);

        $id_reg = auth()->user()->fk_id;
        $aktivitas_mbkm = $request->aktivitas_mbkm;

        $semester_aktif = SemesterAktif::first();
        $krs_aktivitas_mbkm = AktivitasMahasiswa::with(['anggota_aktivitas'])
                        ->whereHas('anggota_aktivitas' , function($query) use ($id_reg) {
                                $query->where('id_registrasi_mahasiswa', $id_reg);
                        })
                        ->where('id_semester', $semester_aktif->id_semester)
                        ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20', '21'])
                        ->get();
         
        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
                    ->first();
        
        $db = new MataKuliah();
        $db_akt = new AktivitasMahasiswa();

        list($krs_akt, $data_akt_ids) = $db_akt->getKrsAkt($id_reg, $semester_aktif->id_semester);

        $sks_max = $db->getSksMax($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_periode_masuk);
        $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $semester_aktif->id_semester, $data_akt_ids);
        $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_prodi);

        $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
        $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
        $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');
        if(!empty($krs_aktivitas_mbkm->first())){
            $sks_akt_mbkm = $krs_aktivitas_mbkm->first()-> sks_aktivitas;
        }else{
            $sks_akt_mbkm =0;
        }

        $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt + $sks_akt_mbkm ;     
        
        // dd($total_sks);

        if (($total_sks + $request->sks_mbkm) > $sks_max) {
            return redirect()->back()->with('error', 'Total SKS tidak boleh melebihi SKS maksimum. Anda sudah Mengambil'.' '.$total_sks.' SKS'.', Aktivitas MBKM yang Anda ambil memiliki SKS Konversi '.$sks_akt_mbkm.' SKS');
        }

        try {
            // Gunakan transaksi untuk memastikan semua operasi database berhasil
            DB::transaction(function () use ($request) {
            $id_reg = auth()->user()->fk_id;

            $riwayat_pendidikan = RiwayatPendidikan::with('prodi')            
                            ->where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

            $semester_aktif = SemesterAktif::with('semester')->first();
            
            $now = Carbon::now();

            // $mk_konversi = MataKuliah::where('id_matkul', $request->id_matkul)->where('id_prodi', $riwayat_pendidikan->id_prodi)->first();
            // dd($request->aktivitas_mbkm);

            $id_aktivitas = Uuid::uuid4()->toString();

            $aktivitas_mbkm = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                            ->where('id_jenis_aktivitas', $request->aktivitas_mbkm)
                            ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                            ->first();
                            // dd($aktivitas_mbkm);

            // Simpan data ke tabel aktivitas_mahasiswas
                $aktivitas=AktivitasMahasiswa::create([
                    'approve_krs' =>0,
                    'approve_sidang' =>0,
                    'feeder'=>0,
                    'id_aktivitas' => $id_aktivitas,
                    'program_mbkm'=>1,
                    'nama_program_mbkm'=>'Flagship',//tanyakan dirapat
                    'jenis_anggota'=>0,
                    'nama_jenis_anggota'=>'Personal',//tanyakan dirapat
                    'id_jenis_aktivitas'=>$aktivitas_mbkm->id_jenis_aktivitas,
                    'nama_jenis_aktivitas'=>$aktivitas_mbkm->nama_jenis_aktivitas,
                    'id_prodi' => $riwayat_pendidikan->id_prodi,
                    'nama_prodi'=>$riwayat_pendidikan->nama_program_studi,
                    'id_semester' => $semester_aktif->id_semester,
                    'nama_semester'=>$semester_aktif->semester->nama_semester,
                    'judul' => $request->judul,
                    'keterangan'=>$request->keterangan,
                    'lokasi'=>$request->lokasi,
                    'sk_tugas'=>Null,
                    'sumber_data'=>1,
                    'tanggal_sk_tugas'=>Null,
                    'tanggal_mulai'=>$now,//tambahkan kondisi jika aktivitas melanjutkan semester
                    'tanggal_selesai'=>Null,
                    'untuk_kampus_merdeka'=>1,
                    'asal_data'=>9,
                    'nm_asaldata'=>NULL,
                    'status_sync'=>'belum sync',
                    'mk_konversi'=>NULL,
                    'sks_aktivitas'=>$request->sks_mbkm,
                    // tambahkan field lain yang diperlukan
                ]);

                $id_anggota = Uuid::uuid4()->toString();

                // Simpan data ke tabel anggota_aktivitas_mahasiswas
                AnggotaAktivitasMahasiswa::create([
                    'feeder'=>0,
                    'id_anggota'=>$id_anggota,
                    'id_aktivitas' => $aktivitas->id_aktivitas,
                    'judul' => $aktivitas->judul,
                    'id_registrasi_mahasiswa'=>$id_reg,
                    'nim'=>$riwayat_pendidikan->nim,
                    'nama_mahasiswa'=> $riwayat_pendidikan->nama_mahasiswa,
                    'jenis_peran'=>2,
                    'nama_jenis_peran'=>'Anggota',
                    'status_sync'=>'belum sync',
                ]);   


                //Generate id aktivitas mengajar
                // $id_bimbing_mahasiswa = Uuid::uuid4()->toString();
                
                // $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)
                //                 ->where('id_dosen', $riwayat_pendidikan->dosen_pa)
                //                 ->first();

                // BimbingMahasiswa::create([
                //     'feeder'=>0,
                //     'approved'=>1,
                //     'approved_dosen'=>1,
                //     'id_bimbing_mahasiswa'=> $id_bimbing_mahasiswa,
                //     'id_aktivitas'=>$aktivitas->id_aktivitas,
                //     'judul'=>$aktivitas->judul,
                //     'id_kategori_kegiatan'=>110300,
                //     'nama_kategori_kegiatan'=>'Membimbing Kuliah Kerja Nyata, Praktek Kerja Nyata, Praktek Kerja Lapangan, termasuk membimbing pelatihan militer mahasiswa, pertukaran mahasiswa,  Magang, kuliah berbasis penelitian, wirausaha, dan bentuk lain pengabdian kepada masyarakat, dan sejenisnya',
                //     'id_dosen'=>$dosen_pembimbing->id_dosen, 
                //     'nidn'=>$dosen_pembimbing->nidn,
                //     'nama_dosen'=>$dosen_pembimbing->nama_dosen,
                //     'pembimbing_ke'=>1,
                //     'status_sync'=>'belum sync',
                // ]);
            });

            // Jika berhasil, kembalikan respons sukses
            return redirect()->route('mahasiswa.perkuliahan.mbkm.pertukaran')->with('success', 'Data aktivitas mahasiswa berhasil disimpan');

        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
            return redirect()->back()->with('error', $e->getMessage());
        }
        
        return redirect()->route('mahasiswa.perkuliahan.mbkm.pertukaran')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function hapusAktivitas_pertukaran($id)
    {
        $id_aktivitas=AktivitasMahasiswa::where('id', $id)->pluck('id_aktivitas');
        // dd($id_aktivitas);
        DB::beginTransaction();

        try {

            $semester_aktif = SemesterAktif::first();
            $today = Carbon::now()->toDateString();

            if($today >= $semester_aktif->krs_mulai && $today <= $semester_aktif->krs_selesai ){
                $batas_isi_krs =  Carbon::parse($semester_aktif->krs_selesai)->toDateString();
            }
            elseif(($today >= $semester_aktif->tanggal_mulai_kprs && $today <= $semester_aktif->tanggal_akhir_kprs )){
                $batas_isi_krs =  Carbon::parse($semester_aktif->tanggal_akhir_kprs)->toDateString();
            }else
            {
                $batas_isi_krs =  NULL;
            }

            if ($today > $batas_isi_krs) {
                return redirect()->back()->with('error', 'Periode Pengajuan Aktivitas MBKM telah berakhir, Data tidak dapat dihapus.');
            }

            // Menghapus bimbingan mahasiswa
            $bimbing = BimbingMahasiswa::where('id_aktivitas', $id_aktivitas);
            // if ($bimbing->first()->approved==1) {
            //     return redirect()->back()->with('error', 'Anda tidak dapat menghapus Aktivitas MBKM ini, Aktivitas MBKM telah disetujui oleh KoProdi');
            // }

            if ($bimbing) {
                $bimbing->delete();
            }
            
            // Menghapus anggota aktivitas mahasiswa
            $anggota = AnggotaAktivitasMahasiswa::where('id_aktivitas', $id_aktivitas);
            if ($anggota) {
                $anggota->delete();
            }
            
            // Menghapus aktivitas mahasiswa
            $aktivitas = AktivitasMahasiswa::findOrFail($id);
            $aktivitas->delete();

            DB::commit();

            return redirect()->route('mahasiswa.perkuliahan.mbkm.pertukaran')->with('success', 'Aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('mahasiswa.perkuliahan.mbkm.pertukaran')->with('error', $e->getMessage());
        }
    }
}
