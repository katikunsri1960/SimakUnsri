<?php

namespace App\Http\Controllers\Fakultas\Akademik;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\FileFakultas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SKYudisiumController extends Controller
{
    public function index(Request $request)
    {
        $fakultas_id = auth()->user()->fk_id;

        $data = FileFakultas::with(['fakultas'])->where('fakultas_id', $fakultas_id)->orderBy('id', 'ASC')->get();
        // dd($data);

        return view('fakultas.data-akademik.wisuda.sk-yudisium.index', compact('data'));
    }

    public function store(Request $request)
    {
        $fakultas_id = auth()->user()->fk_id;
        // dd($request->all());

        $request->validate([
            'tgl_sk_yudisium' => 'required|date',
            'tgl_yudisium' => 'required|date',
            'no_sk_yudisium' => 'required|string|max:255',
            'sk_yudisium_file' => 'required|file|mimes:pdf|max:1024',
        ]);

        
        try {
            $file = $request->file('sk_yudisium_file');
            $skUuid = Uuid::uuid4()->toString();
            $skYudisiumPath = $file->storeAs('wisuda/sk_yudisium',$skUuid . '.' . $file->getClientOriginalExtension(), 'public');
            $sk_yudisium_file = 'storage/' . $skYudisiumPath;

            
            // Tambahkan create ke tabel file_fakultas
            FileFakultas::create([
                'fakultas_id' => $fakultas_id,
                'nama_file' => $request->no_sk_yudisium,
                'tgl_sk_yudisium' => $request->tgl_sk_yudisium,
                'tgl_yudisium' => $request->tgl_yudisium,
                'dir_file' => $sk_yudisium_file,
            ]);

            return redirect()->back()->with('success', 'SK Yudisium berhasil diupload.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Gagal upload SK Yudisium: ' . $e->getMessage());
        }
    }


    public function get_dosen(Request $request)
    {
        $search = $request->get('q');


        $tahun_ajaran = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();

        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran-1)
                                ->orderby('nama_dosen', 'asc');
        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%")
                  ->where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran);
        }

        $data = $query->get();
        // dd($data);

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'id_dosen' => 'required',
            'id_jabatan' => 'required',
            'tgl_mulai_jabatan' => 'required|date',
            'tgl_selesai_jabatan' => 'required|date|after_or_equal:tgl_mulai_jabatan',
            'gelar_depan' => 'nullable|string|max:255',
            'gelar_belakang' => 'nullable|string|max:255',
        ]);

        try {
            // Cari data pejabat fakultas berdasarkan ID
            $data = FileFakultas::findOrFail($id);

            // Update data pejabat
            $data->id_dosen = $request->id_dosen;
            $data->id_jabatan = $request->id_jabatan;
            $data->tgl_mulai_jabatan = $request->tgl_mulai_jabatan;
            $data->tgl_selesai_jabatan = $request->tgl_selesai_jabatan;
            $data->gelar_depan = $request->gelar_depan;
            $data->gelar_belakang = $request->gelar_belakang;

            // Simpan perubahan
            $data->save();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('pejabat-fakultas.index')->with('success', 'Data pejabat berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return redirect()->back()->with('error', 'Terjadi masalah saat menyimpan data. Silakan coba lagi.');
        }
    }


    public function destroy(FileFakultas $id_pejabat)
    {
        $id_pejabat->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
