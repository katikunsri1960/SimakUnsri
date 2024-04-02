<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\MatkulMerdeka;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\RuangPerkuliahan;

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

    public function mahasiswa()
    {

        return view('prodi.data-master.mahasiswa.index');
    }

    public function matkul()
    {
        $data = MataKuliah::where('id_prodi', auth()->user()->fk_id)->get();

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

        $matkul = MataKuliah::where('id_prodi', $id_prodi)
            ->whereNotIn('id_matkul', function($query) use ($id_prodi) {
                $query->select('id_matkul')
                    ->from(with(new MatkulMerdeka)->getTable())
                    ->where('id_prodi', $id_prodi);
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
        return view('prodi.data-master.kurikulum.index');
    }
}
