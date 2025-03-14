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
        // SYARAT UNTUK PENGAJUAN CUTI
        // - Sesuai dengan ketentuan yang berlaku, mahasiswa diperkenankan mengambil cuti kuliah atau
        // - SO (Stop Out), kecuali mahasiswa penerima beasiswa Bidikmisi/KIP-K, mahasiswa penerima
        // - beasiswa penuh lainnya, dan Mahasiswa Program Pendidikan Profesi.
        // - mahasiswa telah menempuh minimal 4 semester untuk program sarjana, 
        // - atau telah menempuh minimal 50% dari total sks yang wajib ditempuh pada program studinya.
        $id_reg = auth()->user()->fk_id;
        
        $semester_aktif = SemesterAktif::first();

        $today = Carbon::now()->toDateString();

        if($semester_aktif->batas_bayar_ukt){
            if($today > $semester_aktif->batas_bayar_ukt){
            // return redirect()->back()->with('error', 'Periode Pengajuan Cuti telah berakhir!');
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
    
    private function calculateMaxCuti($jenjang_pendidikan)
    {
        // Pedoman Akademik dan Kemahasiswaan Universitas Sriwijaya Tahun Akademik 2024/2025
        // Halaman 50
        // k. Lama PKA atau SO maksimum 1 (satu) semester bagi program Diploma dan Program Sarjana.
        // l. Lama PKA atau SO maksimum 2 (dua) semester Program Magister dan Program Doktor,
            // yang dapat diambil berturut-turut atau terpisah.
        // m. Mahasiswa program profesi dan spesialis hanya diperkenankan mengajukan permohonan
            // PKA atau SO satu kali. Apabila akan mengajukan izin meninggalkan kuliah karena alasan
            // penting, maka tetap membayar Uang Kuliah Tunggal (UKT).
            
        if ($jenjang_pendidikan == 30 || $jenjang_pendidikan == 22 ||
            $jenjang_pendidikan == 31 || $jenjang_pendidikan == 32 ||
            $jenjang_pendidikan == 37) {
            return 1;
        } elseif ($jenjang_pendidikan == 35 ||
                    $jenjang_pendidikan == 40) {
            return 2;
        } else {
            return 0;
        }
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
            // return redirect()->back()->with('error', 'Periode Pengajuan Cuti telah berakhir!');
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Anda tidak dapat mengajukan Penundaan Bayar, Periode pengajuan Penundaan Bayar telah berakhir!');
            }
        }

        $existingCuti=$penundaan_bayar->where('id_semester', $semester_aktif->id_semester)
                    ->count();

        if($existingCuti){
            return redirect()->back()->with('error',  'Anda tidak bisa mengajukan cuti, Anda telah mencapai maksimum Penundaan Bayar semester ini!');
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
        $existingCuti = PenundaanBayar::where('id_registrasi_mahasiswa', $id_reg)
        ->where('id_semester', $semester_aktif->id_semester)
        ->first();

        // Jika sudah ada Penundaan Bayar yang sedang diproses, tampilkan pesan error
        if (!empty($existingCuti)) {
            if ($existingCuti->approved == 0) {
                return redirect()->back()->with('error', 'Anda sudah memiliki Penundaan Bayar yang sedang diproses. Tunggu persetujuan atau batalkan pengajuan sebelum membuat pengajuan baru.');
            } elseif ($existingCuti->approved == 1) {
                return redirect()->back()->with('error', 'Anda sudah memiliki Penundaan Bayar yang sudah disetujui.');
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
        $fileName = 'penundaan_bayar' . str_replace(' ', '_', $riwayat_pendidikan->nim) . '.' . $request->file('file_pendukung')->getClientOriginalExtension();

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
            $cuti = PenundaanBayar::where('id', $id)->first();

            // Jika Penundaan Bayar tidak ditemukan, lemparkan pesan error
            if (!$cuti) {
                return redirect()->route('mahasiswa.penundaan-bayar.index')->with('error', 'Pengajuan cuti tidak ditemukan.');
            }

            if ($cuti->approved != 0) {
                return redirect()->route('mahasiswa.penundaan-bayar.index')->with('error', 'Pengajuan cuti tidak dapat dihapus! Pengajuan Cuti sudah disetujui!');
            }

            // Hapus data Penundaan Bayar dari database
            $cuti->delete();

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('mahasiswa.penundaan-bayar.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani error dan tampilkan pesan error
            return redirect()->route('mahasiswa.penundaan-bayar.index')->with('error', 'Terjadi kesalahan saat menghapus Penundaan Bayar.');
        }
    }
}
