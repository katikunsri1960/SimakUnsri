<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\RuangPerkuliahan;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Semester;



class KelasPenjadwalanController extends Controller
{
    public function kelas_penjadwalan()
    {
        $semester_aktif = '20231'; //Ganti dengan semester aktif yang diambil dari database
        $prodi_id = auth()->user()->fk_id;
        $data = MataKuliah::leftjoin('kelas_kuliahs','kelas_kuliahs.id_matkul','mata_kuliahs.id_matkul')
                            ->select('mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','kelas_kuliahs.nama_semester')
                            ->addSelect(DB::raw("(select count(id) from kelas_kuliahs where kelas_kuliahs.id_matkul=mata_kuliahs.id_matkul and kelas_kuliahs.id_semester='$semester_aktif') AS jumlah_kelas_kuliah"))
                            ->where('mata_kuliahs.id_prodi', $prodi_id)
                            ->where('kelas_kuliahs.id_semester', $semester_aktif)
                            ->groupBy('kelas_kuliahs.id_matkul','mata_kuliahs.id_matkul','mata_kuliahs.kode_mata_kuliah','mata_kuliahs.nama_mata_kuliah','kelas_kuliahs.nama_semester')
                            ->get();
        // dd($data);
        return view('prodi.data-akademik.kelas-penjadwalan.index', ['data' => $data]);
    }

    public function detail_kelas_penjadwalan($id_matkul)
    {
        $semester_aktif = '20231'; //Ganti dengan semester aktif yang diambil dari database
        $prodi_id = auth()->user()->fk_id;
        $data = KelasKuliah::leftjoin('ruang_perkuliahans','ruang_perkuliahans.id','ruang_perkuliahan_id')
                            // ->leftjoin('mata_kuliahs','mata_kuliahs.id_matkul','kelas_kuliahs.id_matkul')
                            // ->leftjoin('semesters','semesters.id_semester','kelas_kuliahs.id_semester')
                            ->where('kelas_kuliahs.id_matkul', $id_matkul)
                            ->where('kelas_kuliahs.id_prodi', $prodi_id)
                            ->where('kelas_kuliahs.id_semester', $semester_aktif)
                            ->get();
        // dd($data);
        return view('prodi.data-akademik.kelas-penjadwalan.detail', ['data' => $data, 'id_matkul' => $id_matkul]);
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

    // public function get_matkul(Request $request)
    // {
    //     $search = $request->get('q');
    //     $prodi_id = auth()->user()->fk_id;

    //     $query = MataKuliah::where('id_prodi', $prodi_id)
    //                         ->orderby('nama_mata_kuliah', 'asc');
    //     if ($search) {
    //         $query->where('nama_mata_kuliah', 'like', "%{$search}%")
    //               ->orWhere('kode_mata_kuliah', 'like', "%{$search}%")
    //               ->where('id_prodi', $prodi_id);
    //     }

    //     $data = $query->get();

    //     return response()->json($data);
    // }

    public function kelas_penjadwalan_store(Request $request, $id_matkul)
    {
        // dd($id_matkul);
        //Define variable
        $prodi_id = auth()->user()->fk_id;
        $kelas = KelasKuliah::where('id_prodi',$prodi_id)->get();
        $semester_aktif = Semester::where('id_semester','=','20231')->where('a_periode_aktif','=','1')->get();
        $kode_tahun = substr($semester_aktif[0]['id_semester'],-3);
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
        $check_kelas = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->get();
        // dd(count($check_kelas));
        if(strval($check_lokasi_ruang[0]['lokasi']) == "Indralaya"){
            if(count($check_kelas) <= 70){
                $kode_nama_L = $kode_tahun."L";
                $check_kelas_L = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_L}%")->get();
                if(count($check_kelas_L) < 10){
                    if(count($check_kelas_L) < 9){
                        $nama_kelas_kuliah = $kode_nama_L.count($check_kelas_L)+1;
                    }else{
                        $nama_kelas_kuliah = $kode_nama_L."0";
                    }
                }else{
                    $kode_nama_A = $kode_tahun."A";
                    $check_kelas_A = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_A}%")->get();
                    if(count($check_kelas_A) < 10){
                        if(count($check_kelas_A) < 9){
                            $nama_kelas_kuliah = $kode_nama_A.count($check_kelas_A)+1;
                        }else{
                            $nama_kelas_kuliah = $kode_nama_A."0";
                        }
                    }else{
                        $kode_nama_B = $kode_tahun."B";
                        $check_kelas_B = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_B}%")->get();
                        if(count($check_kelas_B) < 10){
                            if(count($check_kelas_B) < 9){
                                $nama_kelas_kuliah = $kode_nama_B.count($check_kelas_B)+1;
                            }else{
                                $nama_kelas_kuliah = $kode_nama_B."0";
                            }
                        }else{
                            $kode_nama_C = $kode_tahun."C";
                            $check_kelas_C = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_C}%")->get();
                            if(count($check_kelas_C) < 10){
                                if(count($check_kelas_C) < 9){
                                    $nama_kelas_kuliah = $kode_nama_C.count($check_kelas_C)+1;
                                }else{
                                    $nama_kelas_kuliah = $kode_nama_C."0";
                                }
                            }else{
                                $kode_nama_D = $kode_tahun."D";
                                $check_kelas_D = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $request->nama_mata_kuliah)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_D}%")->get();
                                if(count($check_kelas_D) < 10){
                                    if(count($check_kelas_D) < 9){
                                        $nama_kelas_kuliah = $kode_nama_D.count($check_kelas_D)+1;
                                    }else{
                                        $nama_kelas_kuliah = $kode_nama_D."0";
                                    }
                                }else{
                                    $kode_nama_E = $kode_tahun."E";
                                    $check_kelas_E = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_E}%")->get();
                                    if(count($check_kelas_E) < 10){
                                        if(count($check_kelas_E) < 9){
                                            $nama_kelas_kuliah = $kode_nama_E.count($check_kelas_E)+1;
                                        }else{
                                            $nama_kelas_kuliah = $kode_nama_E."0";
                                        }
                                    }else{
                                        $kode_nama_F = $kode_tahun."F";
                                        $check_kelas_F = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_F}%")->get();
                                        if(count($check_kelas_F) < 10){
                                            if(count($check_kelas_F) < 9){
                                                $nama_kelas_kuliah = $kode_nama_F.count($check_kelas_F)+1;
                                            }else{
                                                $nama_kelas_kuliah = $kode_nama_F."0";
                                            }
                                        }else{
                                            $kode_nama_G = $kode_tahun."G";
                                            $check_kelas_G = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_G}%")->get();
                                            if(count($check_kelas_G) < 10){
                                                if(count($check_kelas_G) < 9){
                                                    $nama_kelas_kuliah = $kode_nama_G.count($check_kelas_G)+1;
                                                }else{
                                                    $nama_kelas_kuliah = $kode_nama_G."0";
                                                }
                                            }else{
                                                $kode_nama_H = $kode_tahun."H";
                                                $check_kelas_H = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_H}%")->get();
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
                $check_kelas_P = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_P}%")->get();
                if(count($check_kelas_P) < 10){
                    if(count($check_kelas_P) < 9){
                        $nama_kelas_kuliah = $kode_nama_P.count($check_kelas_P)+1;
                    }else{
                        $nama_kelas_kuliah = $kode_nama_P."0";
                    }
                }else{
                    $kode_nama_M = $kode_tahun."M";
                    $check_kelas_M = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_M}%")->get();
                    if(count($check_kelas_M) < 10){
                        if(count($check_kelas_M) < 9){
                            $nama_kelas_kuliah = $kode_nama_M.count($check_kelas_M)+1;
                        }else{
                            $nama_kelas_kuliah = $kode_nama_M."0";
                        }
                    }else{
                        $kode_nama_N = $kode_tahun."N";
                        $check_kelas_N = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_N}%")->get();
                        if(count($check_kelas_N) < 10){
                            if(count($check_kelas_N) < 9){
                                $nama_kelas_kuliah = $kode_nama_N.count($check_kelas_N)+1;
                            }else{
                                $nama_kelas_kuliah = $kode_nama_N."0";
                            }
                        }else{
                            $kode_nama_R = $kode_tahun."R";
                            $check_kelas_R = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_R}%")->get();
                            if(count($check_kelas_R) < 10){
                                if(count($check_kelas_R) < 9){
                                    $nama_kelas_kuliah = $kode_nama_R.count($check_kelas_R)+1;
                                }else{
                                    $nama_kelas_kuliah = $kode_nama_R."0";
                                }
                            }else{
                                $kode_nama_S = $kode_tahun."S";
                                $check_kelas_S = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_S}%")->get();
                                if(count($check_kelas_S) < 10){
                                    if(count($check_kelas_S) < 9){
                                        $nama_kelas_kuliah = $kode_nama_S.count($check_kelas_S)+1;
                                    }else{
                                        $nama_kelas_kuliah = $kode_nama_S."0";
                                    }
                                }else{
                                    $kode_nama_T = $kode_tahun."T";
                                    $check_kelas_T = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_T}%")->get();
                                    if(count($check_kelas_T) < 10){
                                        if(count($check_kelas_T) < 9){
                                            $nama_kelas_kuliah = $kode_nama_T.count($check_kelas_T)+1;
                                        }else{
                                            $nama_kelas_kuliah = $kode_nama_T."0";
                                        }
                                    }else{
                                        $kode_nama_U = $kode_tahun."U";
                                        $check_kelas_U = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_U}%")->get();
                                        if(count($check_kelas_U) < 10){
                                            if(count($check_kelas_U) < 9){
                                                $nama_kelas_kuliah = $kode_nama_U.count($check_kelas_U)+1;
                                            }else{
                                                $nama_kelas_kuliah = $kode_nama_U."0";
                                            }
                                        }else{
                                            $kode_nama_V = $kode_tahun."V";
                                            $check_kelas_V = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_V}%")->get();
                                            if(count($check_kelas_V) < 10){
                                                if(count($check_kelas_V) < 9){
                                                    $nama_kelas_kuliah = $kode_nama_V.count($check_kelas_V)+1;
                                                }else{
                                                    $nama_kelas_kuliah = $kode_nama_V."0";
                                                }
                                            }else{
                                                $kode_nama_W = $kode_tahun."W";
                                                $check_kelas_W = KelasKuliah::where('id_prodi', $prodi_id)->where('id_matkul', $id_matkul)->where('id_semester', $semester_aktif[0]['id_semester'])->where('nama_kelas_kuliah','LIKE', "{$kode_nama_W}%")->get();
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
        KelasKuliah::create(['ruang_perkuliahan_id'=> $request->ruang_kelas, 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif[0]['id_semester'], 'id_matkul'=> $id_matkul, 'nama_kelas_kuliah'=> $nama_kelas_kuliah, 'tanggal_mulai_efektif'=> $tanggal_mulai_kelas, 'tanggal_akhir_efektif'=> $tanggal_akhir_kelas, 'kapasitas'=> $request->kapasitas_kelas, 'mode'=> $request->mode_kelas, 'lingkup'=> $request->lingkup_kelas, 'jadwal_hari'=> $request->jadwal_hari, 'jadwal_jam_mulai'=> $jam_mulai_kelas, 'jadwal_jam_selesai'=> $jam_selesai_kelas]);

        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }
}
