<?php

namespace App\Http\Controllers\Prodi\Akademik;

use Exception;
use App\Models\cr;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\Konversi;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\AktivitasMahasiswa;

class AktivitasMahasiswaKonversiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodi_id = auth()->user()->fk_id;
        $data = Konversi::where('id_prodi', $prodi_id)->get();

        return view('prodi.data-aktivitas.aktivitas-mahasiswa.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function getMataKuliah($id_kurikulum)
    // {
    //     $mata_kuliah = MataKuliah::whereHas('matkul_kurikulum', function($query) use($id_kurikulum) {
    //         $query->where('id_kurikulum', $id_kurikulum);
    //     })->get();

    //     return response()->json($mata_kuliah);
    // }

    public function create(Request $request)
    {
        $prodi_id = auth()->user()->fk_id;

        $kurikulum_aktif = ListKurikulum::where('id_prodi', $prodi_id)
                    ->where('is_active', 1)
                    ->orderBy('id_semester')
                    ->get();

        $jenis_aktivitas=AktivitasMahasiswa::select('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                    ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                    ->whereNotIn('id_jenis_aktivitas', ['13','14','15','16','17','18','19','20'])
                    ->orderBy('nama_jenis_aktivitas')
                    ->get();

        // $jenjang_pendidikan=ProgramStudi::where('id_prodi', $prodi_id)->pluck('nama_jenjang_pendidikan')->first();

        // dd($jenis_aktivitas);
        return view('prodi.data-aktivitas.aktivitas-mahasiswa.store', compact('kurikulum_aktif', 'jenis_aktivitas'));
    }

    public function get_mk_konversi(Request $request)
    {
        $prodi_id = auth()->user()->fk_id;
        $kurikulum_id = $request->input('kurikulum_id');
        $search = $request->input('q');

        $mk_konversi = MataKuliah::with('matkul_kurikulum')
            ->whereHas('matkul_kurikulum', function($query) use($kurikulum_id) {
                $query->where('id_kurikulum', $kurikulum_id);
            })
            ->where('id_prodi', $prodi_id)
            ->where('nama_mata_kuliah', 'LIKE', "%$search%")
            ->get();

        return response()->json($mk_konversi);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kurikulum' =>'required',
            'jenis_aktivitas' => 'required',
            'mk_konversi' => 'required', 
        ]);

        try {
            // Gunakan transaksi untuk memastikan semua operasi database berhasil
            DB::transaction(function () use ($request) {
            $prodi_id = auth()->user()->fk_id;

            $kurikulum = ListKurikulum::select('id_kurikulum','nama_kurikulum', 'id_prodi', 'nama_program_studi')
                            ->where('id_prodi', $prodi_id)
                            ->where('id_kurikulum', $request->kurikulum)
                            ->first();

            $jenis_aktivitas = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                            ->where('id_jenis_aktivitas', $request->jenis_aktivitas)
                            ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                            ->first();

            $mk_konversi = MataKuliah::with('matkul_kurikulum')
                            ->whereHas('matkul_kurikulum', function($query) use($request) {
                                $query->where('id_kurikulum', $request->kurikulum);
                            })
                            ->where('id_prodi', $prodi_id)
                            ->where('id_matkul', $request->mk_konversi)
                            ->first();
                            // dd($mk_konversi);

            // Simpan data ke tabel aktivitas_mahasiswas
                $koversi=Konversi::create([
                    'id_kurikulum' =>$kurikulum->id_kurikulum,
                    'nama_kurikulum' =>$kurikulum->nama_kurikulum,
                    'id_prodi' =>$kurikulum->id_prodi,
                    'nama_program_studi' =>$kurikulum->nama_program_studi,
                    'id_jenis_aktivitas'=>$jenis_aktivitas->id_jenis_aktivitas,
                    'nama_jenis_aktivitas'=>$jenis_aktivitas->nama_jenis_aktivitas,
                    'id_matkul' =>$mk_konversi->id_matkul,
                    'kode_mata_kuliah' =>$mk_konversi->kode_mata_kuliah,
                    'nama_mata_kuliah' =>$mk_konversi->nama_mata_kuliah,
                ]);
                // dd($koversi);
            });


            
            // Jika berhasil, kembalikan respons sukses
            return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')->with('success', 'Data konversi aktivitas berhasil disimpan');

        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
            return redirect()->back()->with('error', $e->getMessage());
            // return redirect()->back()->with(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }
        
        return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')->with('success', 'Data Berhasil di Tambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try {
            $aktivitas = Konversi::findOrFail($id);
            $aktivitas->delete();

            return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')
                             ->with('success', 'Data berhasil dihapus');
        } catch (Exception $e) {
            return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}