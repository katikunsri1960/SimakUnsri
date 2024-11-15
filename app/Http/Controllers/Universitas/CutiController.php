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
            ]);
            
            // Define variable
            $id_reg = $request->id_registrasi_mahasiswa;
            $semester_aktif=SemesterAktif::with('semester')->first();
            
            $riwayat_pendidikan = RiwayatPendidikan::with('biodata')
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

            $alamat = $riwayat_pendidikan->biodata->jalan . ', ' . $riwayat_pendidikan->biodata->dusun . ', RT-' . $riwayat_pendidikan->biodata->rt . '/RW-' . $riwayat_pendidikan->biodata->rw
            . ', ' . $riwayat_pendidikan->biodata->kelurahan . ', ' . $riwayat_pendidikan->biodata->nama_wilayah;

            $alamat = str_replace(', ,', ',', $alamat);

            // dd($alamat);
            $alasan= $request->alasan_cuti;

            if (!$alasan) {
                $alasan = 'Alasan tidak diisi';
            }

            // dd($alasan);

            // Cek apakah ada file yang diunggah
            if ($request->hasFile('file_pendukung')) {
                // Generate file name
                $fileName = 'file_pendukung_' . str_replace(' ', '_', $riwayat_pendidikan->nama_mahasiswa) . '_' . time() . '.' . $request->file('file_pendukung')->getClientOriginalExtension();
                // Simpan file ke folder public/pdf dengan nama kustom
                $filePath = $request->file('file_pendukung')->storeAs('pdf', $fileName, 'public');
            } else {
                // Jika file tidak diunggah, gunakan nama default
                $filePath = 'pdf/tidak_ada_file.pdf';
            }

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
                'alasan_cuti' => $alasan,
                'file_pendukung' => $filePath,
                'approved' => 2,
                'status_sync' => 'belum sync',
            ]);

            
            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('univ.cuti-kuliah')->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Exception $e) {
            // Tampilkan pesan error jika ada masalah
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id_cuti)
    {
        try {
            // Temukan pengajuan cuti berdasarkan ID
            $cuti = PengajuanCuti::where('id_cuti', $id_cuti)->first();

            // Jika pengajuan cuti tidak ditemukan, lemparkan pesan error
            if (!$cuti) {
                return redirect()->route('univ.cuti-kuliah')->with('error', 'Pengajuan cuti tidak ditemukan.');
            }

            // Hapus file pendukung dari storage jika ada
            // if ($cuti->file_pendukung) {
            //     \Storage::disk('public')->delete($cuti->file_pendukung);
            // }

            // Hapus data pengajuan cuti dari database
            $cuti->delete();

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('univ.cuti-kuliah')->with('success', 'Pengajuan cuti berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani error dan tampilkan pesan error
            return redirect()->route('univ.cuti-kuliah')->with('error', 'Terjadi kesalahan saat menghapus pengajuan cuti.');
        }
    }

}
