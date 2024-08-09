<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Perkuliahan\SubstansiKuliah;
use App\Models\RuangPerkuliahan;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Semester;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\JenisEvaluasi;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\SemesterAktif;
use Ramsey\Uuid\Uuid;



class KelasPenjadwalanController extends Controller
{
    public function kelas_penjadwalan()
    {

        $semester_aktif = SemesterAktif::first();
        // dd($semester_aktif);
        $prodi_id = auth()->user()->fk_id;

        $data = ListKurikulum::with(['mata_kuliah', 'mata_kuliah.kelas_kuliah'])
                ->where('id_prodi', $prodi_id)
                ->where('is_active', 1)
                ->get();
        // dd($data);
        return view('prodi.data-akademik.kelas-penjadwalan.index', ['data' => $data, 'semester_aktif' => $semester_aktif]);
    }

    public function detail_kelas_penjadwalan($id_matkul)
    {
        $semester_aktif = SemesterAktif::first();
        $prodi_id = auth()->user()->fk_id;
        $mata_kuliah = MataKuliah::where('id_matkul', $id_matkul)->first();
        $data = KelasKuliah::with(['dosen_pengajar', 'dosen_pengajar.dosen'])->leftjoin('ruang_perkuliahans','ruang_perkuliahans.id','ruang_perkuliahan_id')
                            ->leftjoin('semesters','semesters.id_semester','kelas_kuliahs.id_semester')
                            ->where('kelas_kuliahs.id_matkul', $id_matkul)
                            ->where('kelas_kuliahs.id_prodi', $prodi_id)
                            ->where('kelas_kuliahs.id_semester', $semester_aktif->id_semester)
                            ->get();
        // dd($data);
        return view('prodi.data-akademik.kelas-penjadwalan.detail', ['data' => $data, 'id_matkul' => $id_matkul, 'matkul' => $mata_kuliah]);
    }

    public function tambah_kelas_penjadwalan($id_matkul)
    {
        // dd($id_matkul);
        $prodi_id = auth()->user()->fk_id;
        $ruang = RuangPerkuliahan::where('id_prodi', $prodi_id)->get();
        $mata_kuliah = MataKuliah::where('id_matkul', $id_matkul)->get();
        // dd($mata_kuliah);
        return view('prodi.data-akademik.kelas-penjadwalan.create', ['ruang' => $ruang, 'mata_kuliah' => $mata_kuliah]);
    }

    public function kelas_penjadwalan_store(Request $request, $id_matkul)
    {
        // dd($id_matkul);
        //Define variable
        $prodi_id = auth()->user()->fk_id;
        $kelas = KelasKuliah::where('id_prodi',$prodi_id)->get();
        $semester_aktif = SemesterAktif::first();
        $id_kelas = Uuid::uuid4()->toString();
        $kode_tahun = substr($semester_aktif->id_semester,-3);
        $tahun_aktif = date('Y');
        $detik = "00";

        //Validate request data
        $data = $request->validate([
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'bulan_mulai' => 'required',
            'bulan_akhir' => 'required',
            'kapasitas_kelas' => 'required',
            'ruang_kelas' => 'required',
            'mode_kelas' => [
                'required',
                Rule::in(['O','F','M'])
            ],
            'lingkup_kelas' => [
                'required',
                Rule::in(['1','2','3'])
            ],
            'jadwal_hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'menit_mulai' => 'required',
            'menit_selesai' => 'required'
        ]);

        //Generate tanggal pelaksanaan
        $tanggal_mulai_kelas = $tahun_aktif."-".$request->bulan_mulai."-".$request->tanggal_mulai;
        $tanggal_akhir_kelas = $tahun_aktif."-".$request->bulan_akhir."-".$request->tanggal_akhir;

        //Generate jam pelaksanaan
        $jam_mulai_kelas = $request->jam_mulai.":".$request->menit_mulai.":".$detik;
        $jam_selesai_kelas = $request->jam_selesai.":".$request->menit_selesai.":".$detik;

        //Generate nama kelas
        $check_lokasi_ruang = RuangPerkuliahan::where('id', $request->ruang_kelas)->get();
        $check_kelas = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->get();
        // dd(count($check_kelas));
        if(strval($check_lokasi_ruang[0]['lokasi']) == "Indralaya"){
            if(count($check_kelas) <= 70){
                $kode_nama_L = $kode_tahun."L";
                $check_kelas_L = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_L}%")->get();
                if(count($check_kelas_L) < 10){
                    if(count($check_kelas_L) < 9){
                        $nama_kelas_kuliah = $kode_nama_L.count($check_kelas_L)+1;
                    }else{
                        $nama_kelas_kuliah = $kode_nama_L."0";
                    }
                }else{
                    $kode_nama_A = $kode_tahun."A";
                    $check_kelas_A = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_A}%")->get();
                    if(count($check_kelas_A) < 10){
                        if(count($check_kelas_A) < 9){
                            $nama_kelas_kuliah = $kode_nama_A.count($check_kelas_A)+1;
                        }else{
                            $nama_kelas_kuliah = $kode_nama_A."0";
                        }
                    }else{
                        $kode_nama_B = $kode_tahun."B";
                        $check_kelas_B = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_B}%")->get();
                        if(count($check_kelas_B) < 10){
                            if(count($check_kelas_B) < 9){
                                $nama_kelas_kuliah = $kode_nama_B.count($check_kelas_B)+1;
                            }else{
                                $nama_kelas_kuliah = $kode_nama_B."0";
                            }
                        }else{
                            $kode_nama_C = $kode_tahun."C";
                            $check_kelas_C = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_C}%")->get();
                            if(count($check_kelas_C) < 10){
                                if(count($check_kelas_C) < 9){
                                    $nama_kelas_kuliah = $kode_nama_C.count($check_kelas_C)+1;
                                }else{
                                    $nama_kelas_kuliah = $kode_nama_C."0";
                                }
                            }else{
                                $kode_nama_D = $kode_tahun."D";
                                $check_kelas_D = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $request->nama_mata_kuliah)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_D}%")->get();
                                if(count($check_kelas_D) < 10){
                                    if(count($check_kelas_D) < 9){
                                        $nama_kelas_kuliah = $kode_nama_D.count($check_kelas_D)+1;
                                    }else{
                                        $nama_kelas_kuliah = $kode_nama_D."0";
                                    }
                                }else{
                                    $kode_nama_E = $kode_tahun."E";
                                    $check_kelas_E = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_E}%")->get();
                                    if(count($check_kelas_E) < 10){
                                        if(count($check_kelas_E) < 9){
                                            $nama_kelas_kuliah = $kode_nama_E.count($check_kelas_E)+1;
                                        }else{
                                            $nama_kelas_kuliah = $kode_nama_E."0";
                                        }
                                    }else{
                                        $kode_nama_F = $kode_tahun."F";
                                        $check_kelas_F = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_F}%")->get();
                                        if(count($check_kelas_F) < 10){
                                            if(count($check_kelas_F) < 9){
                                                $nama_kelas_kuliah = $kode_nama_F.count($check_kelas_F)+1;
                                            }else{
                                                $nama_kelas_kuliah = $kode_nama_F."0";
                                            }
                                        }else{
                                            $kode_nama_G = $kode_tahun."G";
                                            $check_kelas_G = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_G}%")->get();
                                            if(count($check_kelas_G) < 10){
                                                if(count($check_kelas_G) < 9){
                                                    $nama_kelas_kuliah = $kode_nama_G.count($check_kelas_G)+1;
                                                }else{
                                                    $nama_kelas_kuliah = $kode_nama_G."0";
                                                }
                                            }else{
                                                $kode_nama_H = $kode_tahun."H";
                                                $check_kelas_H = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_H}%")->get();
                                                if(count($check_kelas_H) < 10){
                                                    if(count($check_kelas_H) < 9){
                                                        $nama_kelas_kuliah = $kode_nama_H.count($check_kelas_H)+1;
                                                    }else{
                                                        $nama_kelas_kuliah = $kode_nama_H."0";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                return redirect()->back()->with('error', 'Jumlah kelas sudah melebihi batas');
            }
        }else if(strval($check_lokasi_ruang[0]['lokasi']) == "Palembang"){
            if(count($check_kelas) <= 70){
                $kode_nama_P = $kode_tahun."P";
                $check_kelas_P = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_P}%")->get();
                if(count($check_kelas_P) < 10){
                    if(count($check_kelas_P) < 9){
                        $nama_kelas_kuliah = $kode_nama_P.count($check_kelas_P)+1;
                    }else{
                        $nama_kelas_kuliah = $kode_nama_P."0";
                    }
                }else{
                    $kode_nama_M = $kode_tahun."M";
                    $check_kelas_M = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_M}%")->get();
                    if(count($check_kelas_M) < 10){
                        if(count($check_kelas_M) < 9){
                            $nama_kelas_kuliah = $kode_nama_M.count($check_kelas_M)+1;
                        }else{
                            $nama_kelas_kuliah = $kode_nama_M."0";
                        }
                    }else{
                        $kode_nama_N = $kode_tahun."N";
                        $check_kelas_N = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_N}%")->get();
                        if(count($check_kelas_N) < 10){
                            if(count($check_kelas_N) < 9){
                                $nama_kelas_kuliah = $kode_nama_N.count($check_kelas_N)+1;
                            }else{
                                $nama_kelas_kuliah = $kode_nama_N."0";
                            }
                        }else{
                            $kode_nama_R = $kode_tahun."R";
                            $check_kelas_R = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_R}%")->get();
                            if(count($check_kelas_R) < 10){
                                if(count($check_kelas_R) < 9){
                                    $nama_kelas_kuliah = $kode_nama_R.count($check_kelas_R)+1;
                                }else{
                                    $nama_kelas_kuliah = $kode_nama_R."0";
                                }
                            }else{
                                $kode_nama_S = $kode_tahun."S";
                                $check_kelas_S = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_S}%")->get();
                                if(count($check_kelas_S) < 10){
                                    if(count($check_kelas_S) < 9){
                                        $nama_kelas_kuliah = $kode_nama_S.count($check_kelas_S)+1;
                                    }else{
                                        $nama_kelas_kuliah = $kode_nama_S."0";
                                    }
                                }else{
                                    $kode_nama_T = $kode_tahun."T";
                                    $check_kelas_T = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_T}%")->get();
                                    if(count($check_kelas_T) < 10){
                                        if(count($check_kelas_T) < 9){
                                            $nama_kelas_kuliah = $kode_nama_T.count($check_kelas_T)+1;
                                        }else{
                                            $nama_kelas_kuliah = $kode_nama_T."0";
                                        }
                                    }else{
                                        $kode_nama_U = $kode_tahun."U";
                                        $check_kelas_U = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_U}%")->get();
                                        if(count($check_kelas_U) < 10){
                                            if(count($check_kelas_U) < 9){
                                                $nama_kelas_kuliah = $kode_nama_U.count($check_kelas_U)+1;
                                            }else{
                                                $nama_kelas_kuliah = $kode_nama_U."0";
                                            }
                                        }else{
                                            $kode_nama_V = $kode_tahun."V";
                                            $check_kelas_V = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_V}%")->get();
                                            if(count($check_kelas_V) < 10){
                                                if(count($check_kelas_V) < 9){
                                                    $nama_kelas_kuliah = $kode_nama_V.count($check_kelas_V)+1;
                                                }else{
                                                    $nama_kelas_kuliah = $kode_nama_V."0";
                                                }
                                            }else{
                                                $kode_nama_W = $kode_tahun."W";
                                                $check_kelas_W = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif->id_semester)->where('nama_kelas_kuliah','LIKE', "{$kode_nama_W}%")->get();
                                                if(count($check_kelas_W) < 10){
                                                    if(count($check_kelas_W) < 9){
                                                        $nama_kelas_kuliah = $kode_nama_W.count($check_kelas_W)+1;
                                                    }else{
                                                        $nama_kelas_kuliah = $kode_nama_W."0";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }else{
                return redirect()->back()->with('error', 'Jumlah kelas sudah melebihi batas');
            }
        }else{
            return redirect()->back()->with('error', 'Lokasi tidak ada');
        }
        // dd($nama_kelas_kuliah);

        //Store data to table
        KelasKuliah::create(['ruang_perkuliahan_id'=> $request->ruang_kelas, 'feeder' => 0, 'id_kelas_kuliah'=> $id_kelas, 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester, 'id_matkul'=> $id_matkul, 'nama_kelas_kuliah'=> $nama_kelas_kuliah, 'tanggal_mulai_efektif'=> $tanggal_mulai_kelas, 'tanggal_akhir_efektif'=> $tanggal_akhir_kelas, 'kapasitas'=> $request->kapasitas_kelas, 'mode'=> $request->mode_kelas, 'lingkup'=> $request->lingkup_kelas, 'jadwal_hari'=> $request->jadwal_hari, 'jadwal_jam_mulai'=> $jam_mulai_kelas, 'jadwal_jam_selesai'=> $jam_selesai_kelas]);

        return redirect()->route('prodi.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Berhasil di Tambahkan');
    }

    public function dosen_pengajar_kelas($id_matkul,$nama_kelas_kuliah)
    {
        // dd($id_matkul);
        $prodi_id = auth()->user()->fk_id;
        $evaluasi = JenisEvaluasi::get();
        $kelas = KelasKuliah::with(['dosen_pengajar'])->leftjoin('ruang_perkuliahans','ruang_perkuliahans.id','ruang_perkuliahan_id')
                            ->leftjoin('semesters','semesters.id_semester','kelas_kuliahs.id_semester')
                            ->leftjoin('mata_kuliahs','mata_kuliahs.id_matkul','kelas_kuliahs.id_matkul')
                            ->select('*','kelas_kuliahs.tanggal_mulai_efektif','kelas_kuliahs.tanggal_akhir_efektif')
                            ->where('kelas_kuliahs.id_matkul', $id_matkul)
                            ->where('kelas_kuliahs.nama_kelas_kuliah', $nama_kelas_kuliah)
                            ->where('kelas_kuliahs.id_prodi', $prodi_id)
                            ->get();

        // dd($kelas);
        // $pengajar = DosenPengajarKelasKuliah::where('')
        return view('prodi.data-akademik.kelas-penjadwalan.dosen-pengajar', ['evaluasi' => $evaluasi, 'kelas' => $kelas]);
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');
        // $prodi_id = auth()->user()->fk_id;
        $tahun_ajaran = SemesterAktif::with('semester')->first();
        // $tahun_ajaran = Semester::where('id_semester','=','20231')->where('a_periode_aktif','=','1')->get();

        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1)
                                ->orderby('nama_dosen', 'asc');
        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%")
                  ->where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function get_substansi(Request $request)
    {
        $search = $request->get('q');
        $prodi_id = auth()->user()->fk_id;

        // $query = SubstansiKuliah::where('id_prodi', $prodi_id)
                                // ->orderby('nama_substansi', 'asc');
        $query = SubstansiKuliah::orderby('nama_substansi', 'asc');
        if ($search) {
            $query->where('nama_substansi', 'like', "%{$search}%");
                //   ->where('id_prodi', $prodi_id);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function dosen_pengajar_store(Request $request, $id_matkul, $nama_kelas_kuliah)
    {
        // dd($request->all());
        //Define variable
        $prodi_id = auth()->user()->fk_id;
        $kelas = KelasKuliah::where('id_prodi',$prodi_id)->where('id_matkul',$id_matkul)->where('nama_kelas_kuliah',$nama_kelas_kuliah)->get();
        $semester_aktif = SemesterAktif::first();

        //Validate request data
        $data = $request->validate([
            'dosen_kelas_kuliah.*' => 'required',
            'rencana_minggu_pertemuan.*' => 'required',
            'evaluasi.*' => [
                'required',
                Rule::in(['1','2','3','4'])
            ]
        ]);

        //Validasi jumlah total recana minggu pertemuan dosen
        $jumlah_data_pertemuan=count($request->rencana_minggu_pertemuan);
        $rencana_pertemuan = 0;
        for($i=0;$i<$jumlah_data_pertemuan;$i++){
            $rencana_pertemuan = $rencana_pertemuan + $request->rencana_minggu_pertemuan[$i];
        }

        if ($rencana_pertemuan == 0) {
            return redirect()->back()->with('error', 'Rencana Pertemuan tidak boleh 0');
        }
        // dd($rencana_pertemuan);
        try {
            DB::beginTransaction();

            $dosen_pengajar = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->whereIn('id_registrasi_dosen', $request->dosen_kelas_kuliah)->get();

            if($dosen_pengajar->count() == 0 || $dosen_pengajar->count() != count($request->dosen_kelas_kuliah)){
                $dosen_pengajar = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->whereIn('id_registrasi_dosen', $request->dosen_kelas_kuliah)->get();
            }
            //Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen=count($request->dosen_kelas_kuliah);

            for($i=0;$i<$jumlah_dosen;$i++){
                //Generate id aktivitas mengajar
                $id_aktivitas_mengajar = Uuid::uuid4()->toString();
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->where('id_registrasi_dosen', $request->dosen_kelas_kuliah[$i])->first();
                if(!$dosen)
                {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->where('id_registrasi_dosen', $request->dosen_kelas_kuliah[$i])->first();
                }

                if(is_null($request->substansi_kuliah)){
                    //Store data to table tanpa substansi kuliah
                    DosenPengajarKelasKuliah::create(['feeder'=> 0,'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen->id_registrasi_dosen, 'id_dosen'=> $dosen->id_dosen, 'urutan' => $i+1, 'id_kelas_kuliah'=> $kelas[0]['id_kelas_kuliah'], 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);

                }else{
                    //Get sks substansi total
                    $substansi_kuliah = SubstansiKuliah::where('id_substansi',$request->substansi_kuliah[$i])->get();

                    //Store data to table dengan substansi kuliah
                    DosenPengajarKelasKuliah::create(['feeder'=> 0, 'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen->id_registrasi_dosen, 'id_dosen'=> $dosen->id_dosen, 'urutan' => $i+1, 'id_kelas_kuliah'=> $kelas[0]['id_kelas_kuliah'], 'id_substansi' => $substansi_kuliah->first()->id_substansi, 'sks_substansi_total' => $substansi_kuliah->first()->sks_mata_kuliah, 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);
                }

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Berhasil di Tambahkan');


            //
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
        }

    }

    public function dosen_pengajar_destroy($id_matkul, $id_kelas)
    {
        try {
            DB::beginTransaction();

            DosenPengajarKelasKuliah::where('id_kelas_kuliah', $id_kelas)->delete();

            DB::commit();

            return redirect()->route('prodi.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Pengajar Berhasil di Hapus!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Pengajar Gagal di Hapus. '. $th->getMessage());
        }
    }

    public function kelas_penjadwalan_destroy($id_matkul, $id_kelas)
    {
        $peserta = PesertaKelasKuliah::where('id_kelas_kuliah', $id_kelas)->first();

        if($peserta){
            return redirect()->back()->with('error', 'Data Kelas tidak bisa dihapus karena sudah ada peserta');
        }

        try {
            DB::beginTransaction();

            DosenPengajarKelasKuliah::where('id_kelas_kuliah', $id_kelas)->delete();

            KelasKuliah::where('id_kelas_kuliah', $id_kelas)->delete();


            DB::commit();

            return redirect()->route('prodi.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Kelas Berhasil di Hapus!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Kelas Gagal di Hapus. '. $th->getMessage());
        }
    }

    public function edit_kelas_penjadwalan($id_kelas)
    {
        // dd($id_matkul);
        $semester_aktif = SemesterAktif::first();
        $prodi_id = auth()->user()->fk_id;
        $kelas = KelasKuliah::with(['matkul'])
        ->leftJoin('ruang_perkuliahans', 'ruang_perkuliahans.id', 'kelas_kuliahs.ruang_perkuliahan_id')
        ->where('id_kelas_kuliah', $id_kelas)
        ->where('kelas_kuliahs.id_prodi', $prodi_id)
        ->where('kelas_kuliahs.id_semester', $semester_aktif->id_semester)
        ->first();
        // dd($kelas);
        return view('prodi.data-akademik.kelas-penjadwalan.edit', ['kelas' => $kelas]);
    }

    public function kelas_penjadwalan_update(Request $request, $id_matkul, $id_kelas)
    {
        $peserta = PesertaKelasKuliah::where('id_kelas_kuliah', $id_kelas)->first();

        if($peserta){
            return redirect()->back()->with('error', 'Data Kelas tidak bisa dihapus karena sudah ada peserta');
        }

        try {
            DB::beginTransaction();

            $tahun_aktif = date('Y');
            $detik = "00";

            //Validate request data
            $data = $request->validate([
                'tanggal_mulai' => 'required',
                'tanggal_akhir' => 'required',
                'bulan_mulai' => 'required',
                'bulan_akhir' => 'required',
                'kapasitas_kelas' => 'required',
                'ruang_kelas' => 'required',
                'mode_kelas' => [
                    'required',
                    Rule::in(['O','F','M'])
                ],
                'lingkup_kelas' => [
                    'required',
                    Rule::in(['1','2','3'])
                ],
                'jadwal_hari' => 'required',
                'jam_mulai' => 'required',
                'jam_selesai' => 'required',
                'menit_mulai' => 'required',
                'menit_selesai' => 'required'
            ]);

            //Generate tanggal pelaksanaan
            $tanggal_mulai_kelas = $tahun_aktif."-".$request->bulan_mulai."-".$request->tanggal_mulai;
            $tanggal_akhir_kelas = $tahun_aktif."-".$request->bulan_akhir."-".$request->tanggal_akhir;

            //Generate jam pelaksanaan
            $jam_mulai_kelas = $request->jam_mulai.":".$request->menit_mulai.":".$detik;
            $jam_selesai_kelas = $request->jam_selesai.":".$request->menit_selesai.":".$detik;

            KelasKuliah::update(['tanggal_mulai_efektif'=> $tanggal_mulai_kelas, 'tanggal_akhir_efektif'=> $tanggal_akhir_kelas, 'kapasitas'=> $request->kapasitas_kelas, 'mode'=> $request->mode_kelas, 'lingkup'=> $request->lingkup_kelas, 'jadwal_hari'=> $request->jadwal_hari, 'jadwal_jam_mulai'=> $jam_mulai_kelas, 'jadwal_jam_selesai'=> $jam_selesai_kelas])->where('id_kelas_kuliah', $id_kelas);

            DB::commit();

            return redirect()->route('prodi.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Kelas Berhasil di Ubah!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Kelas Gagal di Ubah. '. $th->getMessage());
        }
    }
}
