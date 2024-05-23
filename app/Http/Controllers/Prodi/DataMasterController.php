<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\MatkulMerdeka;
use App\Models\Perkuliahan\PrasyaratMatkul;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\RuangPerkuliahan;
use Illuminate\Support\Facades\DB;

class DataMasterController extends Controller
{
    public function dosen()
    {
        $db = new BiodataDosen();
        $data = $db->list_dosen_prodi(null, auth()->user()->fk_id);

        return view('prodi.data-master.dosen.index', [
            'data' => $data
        ]);
    }

    public function mahasiswa(Request $request)
    {
        $data = RiwayatPendidikan::with(['kurikulum', 'pembimbing_akademik'])->where('id_prodi', auth()->user()->fk_id);

        if ($request->has('angkatan') && $request->input('angkatan') != ''){
            $data = $data->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $request->input('angkatan'));
        }

        $data = $data->orderBy('id_periode_masuk', 'desc')->get();

        $angkatan = RiwayatPendidikan::where('id_prodi', auth()->user()->fk_id)
                ->select('id_periode_masuk')
                ->distinct()
                ->orderBy('id_periode_masuk', 'desc')
                ->get();

        $kurikulum = ListKurikulum::where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();

        $dosDb = new BiodataDosen();
        $dosen = $dosDb->list_dosen_prodi(null, auth()->user()->fk_id);

        return view('prodi.data-master.mahasiswa.index', [
            'data' => $data,
            'angkatan' => $angkatan,
            'kurikulum' => $kurikulum,
            'dosen' => $dosen,
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
        $data = ListKurikulum::with(['mata_kuliah', 'mata_kuliah.prasyarat_matkul', 'mata_kuliah.prasyarat_matkul.matkul_prasyarat'])->where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();
            // dd($data);
        return view('prodi.data-master.mata-kuliah.index', [
            'data' => $data
        ]);
    }

    public function matkul_merdeka()
    {
        $id_prodi = auth()->user()->fk_id;

        $data = MatkulMerdeka::with('matkul')
            ->where('id_prodi', $id_prodi)
            ->get();

        $kurikulum = ListKurikulum::with(['matkul_kurikulum'])->where('id_prodi', $id_prodi)
            ->where('is_active', 1)
            ->pluck('id_kurikulum');

        $matkul = MataKuliah::with(['kurikulum'])->where('id_prodi', $id_prodi)
            ->whereNotIn('id_matkul', function($query) use ($id_prodi) {
                $query->select('id_matkul')
                    ->from(with(new MatkulMerdeka)->getTable())
                    ->where('id_prodi', $id_prodi);
            })->whereHas('kurikulum', function($query) use ($kurikulum){
                $query->whereIn('list_kurikulums.id_kurikulum', $kurikulum);
            })
            ->orderBy('kode_mata_kuliah')
            ->get();

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

    public function tambah_prasyarat(MataKuliah $matkul)
    {
        $id_prodi = auth()->user()->fk_id;

        $db = new MataKuliah();
        $prasyarat = $db->matkul_prodi($id_prodi);
        
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
        $data = ListKurikulum::where('id_prodi', auth()->user()->fk_id)->where('is_active', 1)->get();

        return view('prodi.data-master.kurikulum-angkatan.index',);
    }
}
