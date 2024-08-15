<?php

namespace App\Http\Controllers\Mahasiswa\Cuti;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Validation\Rule;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Referensi\JenisPrestasi;
use Illuminate\Support\Facades\Storage;
use App\Models\Referensi\TingkatPrestasi;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Connection\ConnectionKeuangan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class CutiController extends Controller
{
    public function index()
    {
        // SYARAT UNTUK PENGAJUAN CUTI
        // - Sesuai dengan ketentuan yang berlaku, mahasiswa diperkenankan mengambil cuti kuliah atau
        // - SO (Stop Out), kecuali mahasiswa penerima beasiswa Bidikmisi/KIP-K, mahasiswa penerima
        // - beasiswa penuh lainnya, dan Mahasiswa Program Pendidikan Profesi.
        // - mahasiswa telah menempuh minimal 4 semester untuk program sarjana, 
        // - atau telah menempuh minimal 50% dari total sks yang wajib ditempuh pada program studinya.
        
        $user = auth()->user();
        $id_test = Registrasi::where('rm_nim', $user->username)->pluck('rm_no_test');
        
        $id_semester = SemesterAktif::first()->id_semester;
        $data = PengajuanCuti::where('id_registrasi_mahasiswa', $user->fk_id)->get();
        // dd($data[0]->id_cuti);

        $jenjang_pendidikan = RiwayatPendidikan::with('prodi')->where('id_registrasi_mahasiswa', $user->fk_id)->first();
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)->first();
        
        $semester = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)
                    ->orderBy('id_semester', 'DESC')
                    ->get();
        $semester_ke = $semester->filter(function($item) {
                return substr($item->id_semester, -1) != '3';
            })->count();
            // dd($semester_ke);

        $pengecekan = $this->checkConditions($jenjang_pendidikan, $beasiswa, $data, $semester_ke);

        $tagihan = Tagihan::with('pembayaran')
        ->whereIn('tagihan.nomor_pembayaran', [$id_test, $user->username])
            ->where('kode_periode', $id_semester)
            ->first();

        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)->first();
            // dd($beasiswa);
    
        $statusPembayaran = $tagihan->pembayaran ? $tagihan->pembayaran->status_pembayaran : null;
    
        return view('mahasiswa.pengajuan-cuti.index', [
            'data' => $data,
            'jenjang_pendidikan' => $jenjang_pendidikan,
            'beasiswa' => $beasiswa,
            'tagihan' => $tagihan,
            'statusPembayaran' => $statusPembayaran,
            'max_cuti' => $pengecekan['max_cuti'],
            'showAlert1' => $pengecekan['showAlert1'],
            'showAlert2' => $pengecekan['showAlert2'],
            'showAlert3' => $pengecekan['showAlert3']
        ]);
    }
    
    private function calculateMaxCuti($jenjang_pendidikan, $beasiswa)
    {
        if ($jenjang_pendidikan->prodi->nama_jenjang_pendidikan == 'S1' ||
            ($jenjang_pendidikan->prodi->nama_jenjang_pendidikan == 'D3' && $beasiswa == null)) {
            return 1;
        } elseif ($jenjang_pendidikan->prodi->nama_jenjang_pendidikan == 'S2' ||
                    ($jenjang_pendidikan->prodi->nama_jenjang_pendidikan == 'S3' && $beasiswa == null)) {
            return 2;
        } else {
            return 0;
        }
    }
    
    private function checkConditions($jenjang_pendidikan, $beasiswa, $data, $semester_ke)
    {
        $max_cuti = $this->calculateMaxCuti($jenjang_pendidikan, $beasiswa);
    
        $showAlert1 = false;
        $showAlert2 = false;
        $showAlert3 = false;
    
        if ($max_cuti == 0 && $data->isEmpty()) {
            $showAlert1 = true;
        } elseif ($beasiswa != null) {
            $showAlert2 = true;
        } elseif ($semester_ke <= 4) {
            $showAlert3 = true;
        }
    
        return [
            'max_cuti' => $max_cuti,
            'showAlert1' => $showAlert1,
            'showAlert2' => $showAlert2,
            'showAlert3' => $showAlert3
        ];
    }

    public function tambah()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::with('biodata', 'prodi', 'prodi.fakultas', 'prodi.jurusan')->where('id_registrasi_mahasiswa', $id_reg)->first();
        $semester_aktif=SemesterAktif::with('semester')->first();
        // dd($data);

        return view('mahasiswa.pengajuan-cuti.store', ['data' => $data, 'semester_aktif' => $semester_aktif]);
    }

    
    public function store(Request $request)
    {
        // Define variable
        $id_reg = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first();
        
        $riwayat_pendidikan = RiwayatPendidikan::select('*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

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

        // Validate request data
        $request->validate([
            'jalan' => 'required',
            'kelurahan' => 'required',
            'nama_wilayah' => 'required',
            'handphone' => 'required',
            'alasan_cuti' => 'required',
            'file_pendukung' => 'required|file|mimes:pdf|max:2048',
        ]);

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
    }


    public function delete($id_cuti)
    {
        try {
            // Temukan pengajuan cuti berdasarkan ID
            $cuti = PengajuanCuti::where('id_cuti', $id_cuti)->first();

            // Jika pengajuan cuti tidak ditemukan, lemparkan pesan error
            if (!$cuti) {
                return redirect()->route('mahasiswa.pengajuan-cuti.index')->with('error', 'Pengajuan cuti tidak ditemukan.');
            }

            // Hapus file pendukung dari storage jika ada
            // if ($cuti->file_pendukung) {
            //     \Storage::disk('public')->delete($cuti->file_pendukung);
            // }

            // Hapus data pengajuan cuti dari database
            $cuti->delete();

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('mahasiswa.pengajuan-cuti.index')->with('success', 'Pengajuan cuti berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani error dan tampilkan pesan error
            return redirect()->route('mahasiswa.pengajuan-cuti.index')->with('error', 'Terjadi kesalahan saat menghapus pengajuan cuti.');
        }
    }
}
