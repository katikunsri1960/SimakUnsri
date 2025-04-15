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
use App\Models\SemesterAktif;

class AktivitasMahasiswaKonversiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prodi_id = auth()->user()->fk_id;
        $data = Konversi::where('id_prodi', $prodi_id)->get();
        // dd($data);

        return view('prodi.data-aktivitas.aktivitas-mahasiswa.index', compact('data'));
    }


    public function create(Request $request)
    {
        $prodi_id = auth()->user()->fk_id;

        $kurikulum_aktif = ListKurikulum::where('id_prodi', $prodi_id)
                    ->where('is_active', 1)
                    ->orderBy('id_semester')
                    ->get();

        $jenis_aktivitas=AktivitasMahasiswa::select('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                    ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                    ->whereIn('id_jenis_aktivitas', ['1','2', '3', '4', '5','6', '22'])
                    ->orderBy('nama_jenis_aktivitas')
                    ->get();

        return view('prodi.data-aktivitas.aktivitas-mahasiswa.store', compact('kurikulum_aktif', 'jenis_aktivitas'));
    }

    public function get_mk_konversi(Request $request)
    {
        $prodi_id = auth()->user()->fk_id;
        $kurikulum_id = $request->input('kurikulum_id');

        $id_matkul = Konversi::where('id_prodi', $prodi_id)
                ->where('id_kurikulum', $kurikulum_id)
                ->pluck('id_matkul');

        $search = $request->input('q');

        $mk_konversi = MatkulKurikulum::where('id_kurikulum', $kurikulum_id)
            ->where('id_prodi', $prodi_id)
            ->whereNotIn('id_matkul', $id_matkul)
            ->where('nama_mata_kuliah', 'LIKE', "%$search%")
            ->orWhere('kode_mata_kuliah', 'like', "%{$search}%")
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
            'tipe_penilaian' => 'required',
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
                    'penilaian_langsung' =>$request->tipe_penilaian
                ]);
                // dd($koversi);
            });

            // Jika berhasil, kembalikan respons sukses
            return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')->with('success', 'Data konversi aktivitas berhasil disimpan');

        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
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

        $kurikulum_aktif = ListKurikulum::where('id_prodi', $prodi_id)
                    ->where('is_active', 1)
                    ->orderBy('id_semester')
                    ->get();

        $jenis_aktivitas=AktivitasMahasiswa::select('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                    ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                    ->whereNotIn('id_jenis_aktivitas', ['13','14','15','16','17','18','19','20'])
                    ->orderBy('nama_jenis_aktivitas')
                    ->get();
        $mk_konversi = Konversi::where('id', $id)->first();
        // dd($mk_konversi);

        return view('prodi.data-aktivitas.aktivitas-mahasiswa.update', ['mk_konversi' => $mk_konversi, 'jenis_aktivitas' => $jenis_aktivitas, 'kurikulum_aktif' => $kurikulum_aktif]);
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
            'tipe_penilaian' => 'required',
        ]);

        try {
            // Gunakan transaksi untuk memastikan semua operasi database berhasil
            $prodi_id = auth()->user()->fk_id;
            $semester = SemesterAktif::first();

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

            $latest_mk_konversi = Konversi::where('id', $id)->first();

            $check_mk_konversi = AktivitasMahasiswa::where('id_prodi', $prodi_id)->where('id_semester', $semester->id_semester)->where('mk_konversi', $latest_mk_konversi->id_matkul)->count();
            // dd($check_mk_konversi);
            if($check_mk_konversi > 0 && ($mk_konversi->id_matkul != $latest_mk_konversi->id_matkul)){
                return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')->with('error', 'Aktivitas MK Konversi Sudah di Ambil Mahasiswa.');
            }else{
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
                    'penilaian_langsung' =>$request->tipe_penilaian
                ]);
            }

            return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')->with('success', 'Data Berhasil Diubah');

        } catch (Exception $e) {
            return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')
                            ->with('error', 'Terjadi kesalahan.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try {
            $prodi_id = auth()->user()->fk_id;
            $semester = SemesterAktif::first();

            $latest_mk_konversi = Konversi::where('id', $id)->first();

            $check_mk_konversi = AktivitasMahasiswa::where('id_prodi', $prodi_id)->where('id_semester', $semester->id_semester)->where('mk_konversi', $latest_mk_konversi->id_matkul)->count();
            // dd($check_mk_konversi);
            if($check_mk_konversi > 0){
                return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')->with('error', 'Aktivitas MK Konversi Sudah di Ambil Mahasiswa.');
            }else{
                $aktivitas = Konversi::findOrFail($id);
                $aktivitas->delete();
            }

            return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')
                             ->with('success', 'Data berhasil dihapus');
        } catch (Exception $e) {
            return redirect()->route('prodi.data-aktivitas.aktivitas-mahasiswa.index')
                             ->with('error', 'Terjadi kesalahan.');
        }
    }
}
