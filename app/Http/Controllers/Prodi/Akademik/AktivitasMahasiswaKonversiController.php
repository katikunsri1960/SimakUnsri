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
use App\Models\Perkuliahan\MatkulKurikulum;
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

    // public function index(Request $request)
    // {
    //     $prodi_id = $request->prodi_id; // Pastikan prodi_id berasal dari request atau di-set sebelumnya

    //     // Mengambil data ListKurikulum dengan relasi ke mata_kuliah dan kelas_kuliah
    //     $data = ListKurikulum::with(['mata_kuliah', 'mata_kuliah.kelas_kuliah'])
    //         ->whereHas('mata_kuliah', function($query) use($prodi_id) {
    //             $query->whereHas('kelas_kuliah', function($query) use($prodi_id) {
    //                 $query->where('id_prodi', $prodi_id);
    //             });
    //         })
    //         ->where('id_prodi', $prodi_id)
    //         ->where('is_active', 1)
    //         ->get();

            
    //     // Menghitung jumlah kelas dari tabel kelas_kuliah
    //     $jumlah_kelas = KelasKuliah::where('id_prodi', $prodi_id)->count();

    //     dd($jumlah_kelas);

    //     return response()->json([
    //         'data' => $data,
    //         'jumlah_kelas' => $jumlah_kelas
    //     ]);
    // }

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

        $mk_konversi = MatkulKurikulum::where('id_kurikulum', $kurikulum_id)
            ->where('id_prodi', $prodi_id)
            ->where('nama_mata_kuliah', 'LIKE', "%$search%")
            ->get();

            // dd($mk_konversi);

        return response()->json($mk_konversi);
        // return view('prodi.data-aktivitas.aktivitas-mahasiswa.store', compact('mk_konversi'));
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

            $mk_konversi = MatkulKurikulum::where('id_prodi', $prodi_id)
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
                    'sks_mata_kuliah' =>$mk_konversi->sks_mata_kuliah,
                    'semester' =>$mk_konversi->semester,
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
    public function edit(string $id)
    {
        $prodi_id = auth()->user()->fk_id;
        $mk_konversi = Konversi::where('id', $id)->first();

        return view('prodi.data-aktivitas.aktivitas-mahasiswa.update', ['mk_konversi' => $mk_konversi]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'kurikulum' =>'required',
            'jenis_aktivitas' => 'required',
            'mk_konversi' => 'required', 
        ]);       

        // try {
            // Gunakan transaksi untuk memastikan semua operasi database berhasil
            $prodi_id = auth()->user()->fk_id;

            $kurikulum = ListKurikulum::select('id_kurikulum','nama_kurikulum', 'id_prodi', 'nama_program_studi')
                            ->where('id_prodi', $prodi_id)
                            ->where('id_kurikulum', $request->kurikulum)
                            ->first();

            $jenis_aktivitas = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                            ->where('id_jenis_aktivitas', $request->jenis_aktivitas)
                            ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                            ->first();

            $mk_konversi = MatkulKurikulum::where('id_prodi', $prodi_id)
                            ->where('id_matkul', $request->mk_konversi)
                            ->first();
                            // dd($mk_konversi);

            // Simpan data ke tabel aktivitas_mahasiswas
            Konversi::where('id', $id)
                ->update([
                    'id_kurikulum' =>$kurikulum->id_kurikulum,
                    'nama_kurikulum' =>$kurikulum->nama_kurikulum,
                    'id_prodi' =>$kurikulum->id_prodi,
                    'nama_program_studi' =>$kurikulum->nama_program_studi,
                    'id_jenis_aktivitas'=>$jenis_aktivitas->id_jenis_aktivitas,
                    'nama_jenis_aktivitas'=>$jenis_aktivitas->nama_jenis_aktivitas,
                    'id_matkul' =>$mk_konversi->id_matkul,
                    'kode_mata_kuliah' =>$mk_konversi->kode_mata_kuliah,
                    'nama_mata_kuliah' =>$mk_konversi->nama_mata_kuliah,
                    'sks_mata_kuliah' =>$mk_konversi->sks_mata_kuliah,
                    'semester' =>$mk_konversi->semester,
                ]);
                // dd($koversi);
        
        return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')->with('success', 'Data Berhasil Diubah');
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
