<?php

namespace App\Http\Controllers\Universitas;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Semester;
use App\Models\SOManual;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;

class CutiManualController extends Controller
{
    public function index(Request $request)
    {
        $data = SOManual::with('riwayat')->get();  // Optimasi query eager loading
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        return view('universitas.cuti-manual.index', [
            'semester' => $semester,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'id_semester' => 'required | exists:semesters,id_semester',
            'tanggal_sk' => 'nullable|date',
            'alasan_cuti' => 'required|string|max:255',
            'no_sk' => 'nullable|string|max:50'
        ]);

        try {
            $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', $validatedData['id_registrasi_mahasiswa'])->firstOrFail();

            $id_cuti = Uuid::uuid4()->toString();
            $semester = Semester::where('id_semester', $validatedData['id_semester'])->first();
            
            $data = array_merge($validatedData, [
                'id_cuti' => $id_cuti,
                'nim' => $riwayat->nim,
                'handphone' => $request->handphone,
                'nama_mahasiswa' => $riwayat->nama_mahasiswa,
                'id_prodi' => $riwayat->id_prodi,
                'id_semester' => $semester->id_semester,
                'nama_semester' => $semester->nama_semester,
                'file_pendukung'=> 'Dibuat Manual',
                'status_sync'=> 'belum sync'
            ]);

            // dd($data);

            SOManual::create($data);

            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function update(SOManual $idmanual, Request $request)
    {
        
    }

    public function destroy(SOManual $idmanual)
    {
        try {
            $idmanual->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
