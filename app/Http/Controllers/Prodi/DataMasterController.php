<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\MatkulMerdeka;
use App\Models\Perkuliahan\PrasyaratMatkul;
use App\Models\Perkuliahan\RencanaPembelajaran;
use App\Models\Connection\Usept;
use App\Models\Connection\CourseUsept;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\RuangPerkuliahan;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    public function dosen()
    {
        $db = new BiodataDosen();
        $data = $db->get();

        return view('prodi.data-master.dosen.index', [
            'data' => $data
        ]);
    }

    public function mahasiswa_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = RiwayatPendidikan::with('kurikulum', 'pembimbing_akademik')
            ->where('id_prodi', auth()->user()->fk_id)
            ->orderBy('id_periode_masuk', 'desc'); // Pastikan orderBy di sini

        if ($searchValue) {
            $query = $query->where(function($q) use ($searchValue) {
                $q->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%');
            });
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $query->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $filter);
        }

        // if($request->has('status') && !empty($request->status)) {
        //     // if there is aktif value in the status, change it to ''
        //     if (in_array('aktif', $request->status)) {
        //         $key = array_search('aktif', $request->status);
        //         $request->status[$key] = '';
        //     }
        //     $filterStatus = $request->status;

        //     $query->whereIn('id_jenis_keluar', $filterStatus);
        // }

        $limit = $request->input('length');
        $offset = $request->input('start');

        $data = $query->get();

        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            $columns = ['angkatan', 'nim', 'nama_mahasiswa'];

            if ($columns[$orderColumn] == 'angkatan') {
                if ($orderDirection == 'asc') {
                    $data = $data->sortBy(function($item) {
                        return substr($item->id_periode_masuk, 0, 4);
                    })->values();
                } else {
                    $data = $data->sortByDesc(function($item) {
                        return substr($item->id_periode_masuk, 0, 4);
                    })->values();
                }
            } else {
                if ($orderDirection == 'asc') {
                    $data = $data->sortBy($columns[$orderColumn])->values();
                } else {
                    $data = $data->sortByDesc($columns[$orderColumn])->values();
                }
            }
        }

        $recordsFiltered = $data->count();

        $data = $data->slice($offset, $limit)->values();

        $recordsTotal = RiwayatPendidikan::where('id_prodi', auth()->user()->fk_id)->count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function mahasiswa(Request $request)
    {

        $angkatan = RiwayatPendidikan::where('id_prodi', auth()->user()->fk_id)
                    ->select(DB::raw('LEFT(id_periode_masuk, 4) as angkatan_raw'))
                    ->distinct()
                    ->orderBy('angkatan_raw', 'desc')
                    ->get();

        // dd($angkatan);

        $kurikulum = ListKurikulum::where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();

        $status = [
            ['id' => 'aktif', 'nama' => 'Aktif'],
            ['id' => '1', 'nama' => 'Lulus'],
            ['id' => '2', 'nama' => 'Mutasi'],
            ['id' => '3', 'nama' => 'Dikeluarkan'],
            ['id' => '4', 'nama' => 'Mengundurkan diri'],
            ['id' => '5', 'nama' => 'Putus Studi'],
            ['id' => '6', 'nama' => 'Wafat'],
            ['id' => '7', 'nama' => 'Hilang'],
            ['id' => 'Z', 'nama' => 'Lainnya'],
        ];

        $dosDb = new BiodataDosen();
        $dosen = $dosDb->get();

        return view('prodi.data-master.mahasiswa.index', [
            'angkatan' => $angkatan,
            'kurikulum' => $kurikulum,
            'dosen' => $dosen,
            'status' => $status
        ]);
    }

    public function set_pa(RiwayatPendidikan $mahasiswa, Request $request)
    {
        $data = $request->validate([
            'id_dosen' => 'required',
        ]);

        $mahasiswa->update([
            'dosen_pa' => $data['id_dosen']
        ]);

        return redirect()->back()->with('success', "Data Berhasil di tambahkan");
    }

    public function set_kurikulum(RiwayatPendidikan $mahasiswa, Request $request)
    {
        $validatedData = $request->validate([
            'id_kurikulum' => 'required' // Added exists rule for better validation
        ]);

        try {
            $mahasiswa->update([
                'id_kurikulum' => $validatedData['id_kurikulum']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Data Berhasil di tambahkan'
            ], 200); // Added HTTP status code
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data Gagal di tambahkan',
                'error' => $e->getMessage()
            ], 500); // Added error handling and HTTP status code
        }
    }

    public function set_kurikulum_angkatan(Request $request)
    {
        $data = $request->validate([
            'tahun_angkatan' => 'required',
            'id_kurikulum' => 'required',
        ]);

        $prodi = auth()->user()->fk_id;
        $db = new RiwayatPendidikan();

        $store = $db->set_kurikulum_angkatan($data['tahun_angkatan'], $data['id_kurikulum'], $prodi);

        return redirect()->back()->with($store['status'], $store['message']);

    }

    public function matkul()
    {
        $data = ListKurikulum::with(['mata_kuliah', 'mata_kuliah.prasyarat_matkul', 'mata_kuliah.prasyarat_matkul.matkul_prasyarat'])
                            ->where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();
            // dd($data);
        return view('prodi.data-master.mata-kuliah.index', [
            'data' => $data
        ]);
    }

    public function lihat_rps($matkul)
    {
        $data_matkul = MataKuliah::where('id_matkul', $matkul)->first();
        $data = RencanaPembelajaran::where('id_matkul', $matkul)->get();
            // dd($data);
        return view('prodi.data-master.mata-kuliah.lihat-rps', [
            'data' => $data,
            'matkul' => $data_matkul
        ]);
    }

    public function approved_rps(RencanaPembelajaran $rps, $matkul)
    {
        $rps->where('id_matkul', $matkul)->update([
            'approved' => 1,
        ]);
            // dd($data);
        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function matkul_merdeka()
    {
        $id_prodi = auth()->user()->fk_id;

        $data = MatkulMerdeka::with('matkul')
            ->where('id_prodi', $id_prodi)
            ->get();

        $idMatkulArray = $data->pluck('id_matkul')->toArray();
        // dd($idMatkulArray);
        $matkul = ListKurikulum::with(['mata_kuliah' => function($query) use ($idMatkulArray) {
                $query->whereNotIn('mata_kuliahs.id_matkul', $idMatkulArray);
            }])
            ->where('id_prodi', $id_prodi)
            ->where('is_active', 1)
            ->get();

        // $kurikulum = ListKurikulum::with(['matkul_kurikulum'])->where('id_prodi', $id_prodi)
        //     ->where('is_active', 1)
        //     ->pluck('id_kurikulum');

        // $matkul = MataKuliah::with(['kurikulum'])->where('id_prodi', $id_prodi)
        //     ->whereNotIn('id_matkul', function($query) use ($id_prodi) {
        //         $query->select('id_matkul')
        //             ->from(with(new MatkulMerdeka)->getTable())
        //             ->where('id_prodi', $id_prodi);
        //     })->whereHas('kurikulum', function($query) use ($kurikulum){
        //         $query->whereIn('list_kurikulums.id_kurikulum', $kurikulum);
        //     })
        //     ->orderBy('kode_mata_kuliah')
        //     ->get();

        return view('prodi.data-master.matkul-merdeka.index', compact('matkul', 'data'));
    }

    public function matkul_merdeka_store(Request $request)
    {

        $data = $request->validate([
                    'id_matkul' => 'required',
                ]);

        $data['id_prodi'] = auth()->user()->fk_id;

        MatkulMerdeka::create($data);

        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }

    public function matkul_merdeka_destroy(MatkulMerdeka $matkul_merdeka)
    {
        $matkul_merdeka->delete();

        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }

    public function ruang_perkuliahan()
    {
        $prodi_id = auth()->user()->fk_id;
        $data = RuangPerkuliahan::where('id_prodi',$prodi_id)->get();

        return view('prodi.data-master.ruang-perkuliahan.index', [
            'data' => $data
        ]);
    }

    public function ruang_perkuliahan_store(Request $request)
    {
        $prodi_id = auth()->user()->fk_id;
        $data = $request->validate([
            'nama_ruang' => 'required',
            'lokasi' => [
                'required',
                Rule::unique('ruang_perkuliahans')->where(function ($query) use($request,$prodi_id) {
                    return $query->where('nama_ruang', $request->nama_ruang)
                    ->where('lokasi', $request->lokasi)
                    ->where('id_prodi', $prodi_id);
                }),
            ],
        ]);

        RuangPerkuliahan::create(['nama_ruang'=> $request->nama_ruang, 'lokasi'=> $request->lokasi, 'id_prodi'=> $prodi_id]);

        return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
    }

    public function ruang_perkuliahan_update(Request $request, RuangPerkuliahan $ruang_perkuliahan)
    {
        $prodi_id = auth()->user()->fk_id;
        $data = $request->validate([
            'nama_ruang' => 'required',
            'lokasi' => [
                'required',
                Rule::unique('ruang_perkuliahans')->where(function ($query) use($request,$prodi_id,$ruang_perkuliahan) {
                    return $query->where('nama_ruang', $request->nama_ruang)
                    ->where('lokasi', $request->lokasi)
                    ->where('id_prodi', $prodi_id)
                    ->whereNotIn('id', [$ruang_perkuliahan->id]);
                }),
            ],
        ]);

        $ruang_perkuliahan->update($data);

        return redirect()->back()->with('success', 'Data Berhasil di Rubah');
    }

    public function ruang_perkuliahan_destroy(RuangPerkuliahan $ruang_perkuliahan)
    {
        $ruang_perkuliahan->delete();

        return redirect()->back()->with('success', 'Data Berhasil di Hapus');
    }

    public function kurikulum()
    {
        $data = ListKurikulum::where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();
        return view('prodi.data-master.kurikulum.index',
        [
            'data' => $data
        ]);
    }

    public function detail_kurikulum(ListKurikulum $kurikulum)
    {
        if ($kurikulum->id_prodi != auth()->user()->fk_id) {
            abort(403);
        }

        $data = $kurikulum->load('matkul_kurikulum');

        return view('prodi.data-master.kurikulum.detail-kurikulum', [
            'data' => $data,
        ]);
    }

    public function tambah_prasyarat(ListKurikulum $kurikulum, MataKuliah $matkul)
    {
        $id_prodi = auth()->user()->fk_id;

        $db = new MataKuliah();
        // $prasyarat = $db->matkul_prodi($id_prodi);
        $prasyarat = ListKurikulum::with('mata_kuliah')
                    ->whereHas('mata_kuliah', function($query) use ($matkul) {
                        $query->where('mata_kuliahs.id_matkul', $matkul->id_matkul);
                    })
                    ->where('id_prodi', $id_prodi)->where('id_kurikulum', $kurikulum->id_kurikulum)->first();
        // dd($prasyarat);
        return view('prodi.data-master.mata-kuliah.tambah-prasyarat', [
            'matkul' => $matkul,
            'prasyarat' => $prasyarat
        ]);
    }

    public function tambah_prasyarat_store(Request $request, MataKuliah $matkul)
    {
        $data = $request->validate([
            'prasyarat' => 'required',
            'prasyarat.*' => 'required|exists:mata_kuliahs,id_matkul'
        ]);
        $db = new PrasyaratMatkul();

        $store = $db->prasyarat_store($matkul->id_matkul, $data['prasyarat']);

        return redirect()->route('prodi.data-master.mata-kuliah')->with($store['status'], $store['message']);
    }

    public function hapus_prasyarat(MataKuliah $matkul)
    {
        $db = new PrasyaratMatkul();

        $data = $db->prasyarat_destroy($matkul->id_matkul);

        return redirect()->back()->with($data['status'], $data['message']);
    }

    public function kurikulum_angkatan()
    {
        $data = ListKurikulum::where('id_prodi', auth()->user()->fk_id)
                    ->where('is_active', 1)->get();

        return view('prodi.data-master.kurikulum-angkatan.index',);
    }

    public function histori_nilai_usept($mahasiswa)
    {
        $data_mahasiswa = RiwayatPendidikan::with('biodata')->where('id_registrasi_mahasiswa', $mahasiswa)->first();
        $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $data_mahasiswa->id_kurikulum)->first();
        $nilai_usept_mhs = Usept::whereIn('nim', [$data_mahasiswa->nim, $data_mahasiswa->biodata->nik])->get();
        $db_course_usept = new CourseUsept;
        $nilai_course = $db_course_usept->whereIn('nim', [$data_mahasiswa->nim, $data_mahasiswa->biodata->nik])->get();

        // dd($nilai_hasil_course);
        return view('prodi.data-master.mahasiswa.nilai-usept', ['data' => $nilai_usept_mhs, 'usept_prodi' => $nilai_usept_prodi, 'course_data' => $nilai_course, 'mahasiswa' => $data_mahasiswa]);
    }
}
