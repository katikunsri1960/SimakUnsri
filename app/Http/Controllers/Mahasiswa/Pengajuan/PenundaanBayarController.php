<?php

namespace App\Http\Controllers\Mahasiswa\Pengajuan;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\BeasiswaMahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\PenundaanBayar;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class PenundaanbayarController extends Controller
{
    public function index()
    {
        $id_reg = auth()->user()->fk_id;
        
        $semester_aktif = SemesterAktif::first();

        $today = Carbon::now()->toDateString();

        if($semester_aktif->batas_bayar_ukt){
            if($today > $semester_aktif->batas_bayar_ukt){
            // return redirect()->back()->with('error', 'Periode Pengajuan Penundaan Bayar telah berakhir!');
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak dapat mengajukan Penundaan Bayar, Periode pengajuan Penundaan Bayar telah berakhir!');
            }
        }

        $data = PenundaanBayar::with('semester', 'riwayat')->where('id_registrasi_mahasiswa', $id_reg)
                            ->get();
                            // dd($data);

        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->count();

        if ($beasiswa > 0) {
            return redirect()->back()->with('error',  'Anda tidak bisa mengajukan Penundaan Bayar, Anda merupakan mahasiswa penerima Beasiswa');
        } 

        return view('mahasiswa.pengajuan.penundaan-bayar.index', [
            'data' => $data,
            'beasiswa' => $beasiswa,
        ]);
    }
    
    public function tambah()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;

        $data = RiwayatPendidikan::with('biodata', 'prodi', 'prodi.fakultas', 'prodi.jurusan')->where('id_registrasi_mahasiswa', $id_reg)->first();
        $semester_aktif=SemesterAktif::with('semester')->first();
        $today = Carbon::now()->toDateString();
        $penundaan_bayar = PenundaanBayar::where('id_registrasi_mahasiswa', $id_reg)
                            ->get();

        if($semester_aktif->batas_bayar_ukt){
            if($today > $semester_aktif->batas_bayar_ukt){
                return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak dapat mengajukan Penundaan Bayar, Periode pengajuan Penundaan Bayar telah berakhir!');
            }
        }

        $existingData=$penundaan_bayar->where('id_semester', $semester_aktif->id_semester)
                    ->count();

        if($existingData){
            return redirect()->back()->with('error',  'Anda tidak bisa mengajukan Penundaan Bayar, Anda telah mencapai maksimum Penundaan Bayar semester ini!');
        }

        return view('mahasiswa.pengajuan.penundaan-bayar.store', ['data' => $data, 'semester_aktif' => $semester_aktif]);
    }

    
    public function store(Request $request)
    {
        // Define variable
        $id_reg = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first();
        
        $riwayat_pendidikan = RiwayatPendidikan::select('*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        // Cek apakah sudah ada Penundaan Bayar yang sedang diproses
        $existingData = PenundaanBayar::where('id_registrasi_mahasiswa', $id_reg)
        ->where('id_semester', $semester_aktif->id_semester)
        ->first();

        // Jika sudah ada Penundaan Bayar yang sedang diproses, tampilkan pesan error
        if ($existingData) {
            if ($existingData->approved == 0) {
                return redirect()->back()->with('error', 'Anda sudah memiliki Penundaan Bayar yang sedang diproses. Tunggu persetujuan atau batalkan pengajuan sebelum membuat pengajuan baru.');
            } elseif ($existingData->approved > 0) {
                return redirect()->back()->with('error', 'Anda sudah memiliki Penundaan Bayar yang sudah diproses!');
            }
        }

        // Validate request data
        $request->validate([
            'jalan' => 'required',
            'kelurahan' => 'required',
            'nama_wilayah' => 'required',
            'handphone' => 'required',
            'alasan' => 'required',
            'file_pendukung' => 'required|file|mimes:pdf|max:2048',
        ]);

        $id = Uuid::uuid4()->toString();

        $alamat = $request->jalan . ', ' . $request->dusun . ', RT-' . $request->rt . '/RW-' . $request->rw
        . ', ' . $request->kelurahan . ', ' . $request->nama_wilayah;

        $alamat = str_replace(', ,', ',', $alamat);

        // Generate file name
        $fileName = 'penundaan_bayar_' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '_' . $semester_aktif->id_semester . '.' . $request->file('file_pendukung')->getClientOriginalExtension();

        // Simpan file ke folder public/pdf dengan nama kustom
        $filePath = $request->file('file_pendukung')->storeAs('penundaan_bayar', $fileName, 'public');

        // Cek apakah file berhasil diupload
        if (!$filePath) {
            return redirect()->back()->with('error', 'File pendukung gagal diunggah. Silakan coba lagi.');
        }

        PenundaanBayar::create([
            'id' => $id,
            'id_registrasi_mahasiswa' => $id_reg,
            'nim'=>$riwayat_pendidikan->nim,
            'id_semester' => $semester_aktif->id_semester,
            // 'id_prodi'=>$riwayat_pendidikan->id_prodi,
            // 'alamat'=> $alamat,
            // 'handphone' => $request->handphone,
            'keterangan' => $request->alasan,
            'file_pendukung' => $filePath,
            'status' => 0,
            'status_sync' => 'belum sync',
        ]);

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('mahasiswa.penundaan-bayar.index')->with('success', 'Data Berhasil di Tambahkan');
    }


    public function delete($id)
    {
        try {
            // Temukan Penundaan Bayar berdasarkan ID
            $penundaan = PenundaanBayar::find($id);

            // Jika Penundaan Bayar tidak ditemukan, lemparkan pesan error
            if (!$penundaan) {
                return redirect()->route('mahasiswa.penundaan-bayar.index')->with('error', 'Pengajuan Penundaan Bayar tidak ditemukan.');
            }

            if ($penundaan->approved != 0) {
                return redirect()->route('mahasiswa.penundaan-bayar.index')->with('error', 'Pengajuan Penundaan Bayar tidak dapat dihapus! Pengajuan Penundaan Bayar sudah disetujui!');
            }

            // Hapus file pendukung dari storage
            if ($penundaan->file_pendukung) {
                Storage::disk('public')->delete($penundaan->file_pendukung);
            }

            // Hapus data Penundaan Bayar dari database
            $penundaan->delete();

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('mahasiswa.penundaan-bayar.index')->with('success', 'Pengajuan Penundaan Bayar berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani error dan tampilkan pesan error
            return redirect()->route('mahasiswa.penundaan-bayar.index')->with('error', 'Terjadi kesalahan saat menghapus Penundaan Bayar.');
        }
    }
}
