<?php

namespace App\Http\Controllers\Mahasiswa\Cuti;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Semester;
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
use App\Models\Perkuliahan\ListKurikulum;
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
        
        $semester_aktif = SemesterAktif::first();

        $today = Carbon::now()->toDateString();

        if($semester_aktif->tgl_mulai_pengajuan_cuti && $semester_aktif->tgl_selesai_pengajuan_cuti){
            if($today < $semester_aktif->tgl_mulai_pengajuan_cuti || $today > $semester_aktif->tgl_selesai_pengajuan_cuti ){
            // return redirect()->back()->with('error', 'Periode Pengajuan Cuti telah berakhir!');
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Periode Pengajuan Cuti telah berakhir!');
            }
        }

        // dd($semester_aktif->tgl_mulai_pengajuan_cuti, $today);
        
        
        $user = auth()->user();
        $nim = $user->username;

        // dd($riwayat_pendidikan);
        $id_test = Registrasi::where('rm_nim', $user->username)->pluck('rm_no_test')->first();
        
        $id_semester = SemesterAktif::first()->id_semester;

        $data = PengajuanCuti::where('id_registrasi_mahasiswa', $user->fk_id)->get();

        $jenjang_pendidikan = RiwayatPendidikan::with('prodi')
                    ->where('id_registrasi_mahasiswa', $user->fk_id)
                    ->first();

        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)
                    ->count();
        
        $semester = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)
                    ->orderBy('id_semester', 'DESC')
                    ->get();

        $semester_ke = $semester->filter(function($item) {
                return substr($item->id_semester, -1) != '3';
            })->count();
            
        $pengecekan = $this->checkConditions($jenjang_pendidikan->prodi->id_jenjang_pendidikan, $beasiswa, $data, $semester_ke, $user->fk_id, $id_semester);
        // dd($jenjang_pendidikan->prodi->id_jenjang_pendidikan);

        $tagihan = Tagihan::with('pembayaran')
        ->whereIn('tagihan.nomor_pembayaran', [$id_test, $nim])
            ->where('kode_periode', $id_semester)
            ->first();

        // $ = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)->first();
            // dd($tagihan);
    
        if($tagihan){
            if($tagihan->pembayaran){
                $statusPembayaran = $tagihan->pembayaran->status_pembayaran;
            }else{
                $statusPembayaran = NULL;
            }
        }else{
            $statusPembayaran = NULL;
        }
    
        return view('mahasiswa.pengajuan-cuti.index', [
            'data' => $data,
            'jenjang_pendidikan' => $jenjang_pendidikan,
            'beasiswa' => $beasiswa,
            'tagihan' => $tagihan,
            'statusPembayaran' => $statusPembayaran,
            'max_cuti' => $pengecekan['max_cuti'],
            'showAlert1' => $pengecekan['showAlert1'],
            'showAlert2' => $pengecekan['showAlert2'],
            'showAlert3' => $pengecekan['showAlert3'],
            'showAlert4' => $pengecekan['showAlert4']
        ]);
    }
    
    private function calculateMaxCuti($jenjang_pendidikan, $beasiswa)
    {
        // if ($beasiswa = 0) {
            if ($jenjang_pendidikan == 30 ||
                        $jenjang_pendidikan == 22) {
                return 1;
            } elseif ($jenjang_pendidikan == 35 ||
                        $jenjang_pendidikan == 40) {
                return 2;
            } else {
                return 0;
            }
        // } else {
        //     return 0;
        // }
    }
    
    private function checkConditions($jenjang_pendidikan, $beasiswa, $data, $semester_ke, $fk_id, $id_semester)
    {
        $max_cuti = $this->calculateMaxCuti($jenjang_pendidikan, $beasiswa);
    
        $showAlert1 = false;
        $showAlert2 = false;
        $showAlert3 = false;
    
        if ($beasiswa > 0) {
            $showAlert2 = true;
        } elseif  ($max_cuti == 0 && $data->isEmpty()){
            $showAlert1 = true;
        } elseif ($semester_ke <= 4) {
            $showAlert3 = true;
        }

        // CARI 50% SKS TOTAL 
        // $user = auth()->user();

        $riwayat_pendidikan = RiwayatPendidikan::with('pembimbing_akademik')
                    ->select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $fk_id)
                    ->first();
        
        $akm = Semester::orderBy('id_semester', 'ASC')
                ->whereBetween('id_semester', [$riwayat_pendidikan->id_periode_masuk, $id_semester])
                ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                ->pluck('id_semester');


        $index_semester_terakhir = $akm->search($akm->last());

        // Pastikan bahwa indeks tidak berada di posisi pertama
        if ($index_semester_terakhir > 0) {
            // Mundur satu semester dari yang terakhir
            $akm_sebelum = $akm[$index_semester_terakhir - 1];
        } else {
            // Jika tidak ada semester sebelumnya (semester pertama), bisa didefinisikan logika lain
            $akm_sebelum = null;
        }

        $jumlah_sks_lulus = ListKurikulum::where('id_kurikulum', $riwayat_pendidikan->id_kurikulum)
                        ->pluck('jumlah_sks_lulus')->first();

        $jumlah_sks_diambil = intval(
                        AktivitasKuliahMahasiswa::where('id_semester', $akm_sebelum)
                        ->where('id_registrasi_mahasiswa', $fk_id)
                        ->pluck('sks_total')
                        ->first()
                    );

                    // dd($jumlah_sks_lulus,
                    // $jumlah_sks_diambil, $akm_sebelum);
        $showAlert4 = false;
        
        if ($jumlah_sks_diambil < ($jumlah_sks_lulus/2)) {
            $showAlert4 = true;
        } 
        
        return [
            'max_cuti' => $max_cuti,
            'showAlert1' => $showAlert1,
            'showAlert2' => $showAlert2,
            'showAlert3' => $showAlert3,
            'showAlert4' => $showAlert4
        ];
    }

    public function tambah()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::with('biodata', 'prodi', 'prodi.fakultas', 'prodi.jurusan')->where('id_registrasi_mahasiswa', $id_reg)->first();
        $semester_aktif=SemesterAktif::with('semester')->first();
        $today = Carbon::now()->toDateString();

        if($semester_aktif->tgl_mulai_pengajuan_cuti && $semester_aktif->tgl_selesai_pengajuan_cuti){
            if($today < $semester_aktif->tgl_mulai_pengajuan_cuti || $today > $semester_aktif->tgl_selesai_pengajuan_cuti ){
            // return redirect()->back()->with('error', 'Periode Pengajuan Cuti telah berakhir!');
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Periode Pengajuan Cuti telah berakhir!');
            }
        }
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

            if ($cuti->approved != 0) {
                return redirect()->route('mahasiswa.pengajuan-cuti.index')->with('error', 'Pengajuan cuti tidak dapat dihapus! Pengajuan Cuti sudah disetujui!');
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
