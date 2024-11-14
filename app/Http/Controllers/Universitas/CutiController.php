<?php

namespace App\Http\Controllers\Universitas;

use Ramsey\Uuid\Uuid;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Mahasiswa\RiwayatPendidikan;

class CutiController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $data = $db->with(['riwayat', 'prodi']);

        if ($request->has('semester')) {
            $data = $data->where('id_semester', $request->semester);
        }

        $data = $data->get();
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        // dd($data);
        return view('universitas.cuti.index', [
            'semester' => $semester,
            'data' => $data,
        ]);
    }

    public function get_mahasiswa(Request $request)
    {
        $db = new RiwayatPendidikan();

        $data = $db->with('biodata', 'prodi')
                    ->where('nim', 'like', '%'.$request->q.'%')
                    ->orWhere('nama_mahasiswa', 'like', '%'.$request->q.'%')
                    ->orderBy('id_periode_masuk', 'desc')->get();

       // Menambahkan semester aktif ke dalam data response
        return response()->json([
            'data' => $data
        ]);
    }
    
    public function getMahasiswaData($id_registrasi_mahasiswa)
    {
        $mahasiswa = RiwayatPendidikan::with('biodata', 'prodi', 'prodi.fakultas', 'prodi.jurusan')
            ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
            ->first();

        // Ambil semester aktif
        $semester_aktif = SemesterAktif::with('semester')->first();

        // Mengirim data mahasiswa dan semester aktif
        return response()->json([
            'data' => $mahasiswa,
            'semester_aktif' => $semester_aktif
        ]);
    }



    public function store(Request $request)
    { 
        try {
            // Validate request data
            $request->validate([
                'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
                'id_semester' => 'required|exists:semesters,id_semester',
                'jalan' => 'required',
                'kelurahan' => 'required',
                'nama_wilayah' => 'required',
                'handphone' => 'required',
                'alasan_cuti' => 'required',
                'file_pendukung' => 'required|file|mimes:pdf|max:2048',
            ]);
            
            // Define variable
            $id_reg = $request->input('id_registrasi_mahasiswa');
            $semester_aktif=SemesterAktif::with('semester')->first();
            
            $riwayat_pendidikan = RiwayatPendidikan::select('*')
                        ->where('id_registrasi_mahasiswa', $id_reg)
                        ->first();
            // dd($id_reg);
            // Cek apakah sudah ada pengajuan cuti yang sedang diproses
            $existingCuti = PengajuanCuti::where('id_registrasi_mahasiswa', $id_reg)
            ->where('id_semester', $semester_aktif->id_semester)
            ->first();

            // Jika sudah ada pengajuan cuti yang sedang diproses, tampilkan pesan error
            if (!empty($existingCuti)) {
                if ($existingCuti->approved == 0) {
                    return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sedang diproses. Tunggu persetujuan atau batalkan pengajuan sebelum membuat pengajuan baru.');
                } elseif ($existingCuti->approved == 1) {
                    return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sudah disetujui.');
                }
            }

            $id_cuti = Uuid::uuid4()->toString();

            $alamat = $request->jalan . ', ' . $request->dusun . ', RT-' . $request->rt . '/RW-' . $request->rw
            . ', ' . $request->kelurahan . ', ' . $request->nama_wilayah;

            $alamat = str_replace(', ,', ',', $alamat);

            // Generate file name
            $fileName = 'file_pendukung_' . str_replace(' ', '_', $riwayat_pendidikan->nama_mahasiswa) . '_' . time() . '.' . $request->file('file_pendukung')->getClientOriginalExtension();

            // Simpan file ke folder public/pdf dengan nama kustom
            $filePath = $request->file('file_pendukung')->storeAs('pdf', $fileName, 'public');

            // Cek apakah file berhasil diupload
            if (!$filePath) {
                return redirect()->back()->with('error', 'File pendukung gagal diunggah. Silakan coba lagi.');
            }

            PengajuanCuti::create([
                'id_cuti' => $id_cuti,
                'id_registrasi_mahasiswa' => $id_reg,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'nim'=>$riwayat_pendidikan->nim,
                'id_semester' => $semester_aktif->id_semester,
                'nama_semester'=> $semester_aktif->semester->nama_semester,
                'id_prodi'=>$riwayat_pendidikan->id_prodi,
                'alamat'=> $alamat,
                'handphone' => $request->handphone,
                'alasan_cuti' => $request->alasan_cuti,
                'file_pendukung' => $filePath,
                'approved' => 0,
                'status_sync' => 'belum sync',
            ]);

            
            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('mahasiswa.pengajuan-cuti.index')->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Exception $e) {
            // Tampilkan pesan error jika ada masalah
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
