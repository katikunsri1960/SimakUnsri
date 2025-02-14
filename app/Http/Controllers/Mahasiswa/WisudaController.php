<?php

namespace App\Http\Controllers\Mahasiswa;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;

class WisudaController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $riwayat_pendidikan = RiwayatPendidikan::with('prodi', 'prodi.fakultas', 'prodi.jurusan')
                    ->where('id_registrasi_mahasiswa', $user->fk_id)
                    ->first();
        
        $aktivitas = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa', 'nilai_konversi'])
                ->whereHas('bimbing_mahasiswa', function ($query) {
                    $query->whereNotNull('id_bimbing_mahasiswa');
                })
                ->whereHas('anggota_aktivitas_personal', function ($query) use ($riwayat_pendidikan) {
                    $query->where('id_registrasi_mahasiswa', $riwayat_pendidikan->id_registrasi_mahasiswa)
                        ->where('nim', $riwayat_pendidikan->nim);
                })
                ->whereHas('nilai_konversi', function ($query) {
                    $query->where('nilai_indeks', '>', 0.00);
                })
                // ->where('id_semester', $semester_aktif)
                ->where('id_prodi', $riwayat_pendidikan->id_prodi)
                ->whereIn('id_jenis_aktivitas', ['2', '3', '1', '22'])
                ->first();

        // dd($aktivitas);

        // $aktivitas = $aktivitas->load(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi', 'konversi', 'uji_mahasiswa']);
        // $data_pelaksanaan_sidang = $aktivitas->load(['revisi_sidang', 'notulensi_sidang', 'penilaian_sidang', 'revisi_sidang.dosen', 'penilaian_sidang.dosen']);

        // $pembimbing_ke = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
        //                     ->first()->pembimbing_ke;
                    
        // $dosen_pembimbing = $aktivitas->load(['bimbing_mahasiswa']);
        // dd($dosen_pembimbing);
        return view('mahasiswa.wisuda.index', [
            'aktivitas' => $aktivitas,
            // 'data_pelaksanaan' => $data_pelaksanaan_sidang,
            // 'dosen_pembimbing' => $dosen_pembimbing,
            // 'aktivitas' => $aktivitas,
            // 'pembimbing_ke' => $pembimbing_ke,
        ]);
    }

    public function tambah()
    {
        // dd($semester_aktif->id_semester);
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::with('biodata', 'prodi', 'prodi.fakultas', 'prodi.jurusan')->where('id_registrasi_mahasiswa', $id_reg)->first();
        $semester_aktif=SemesterAktif::with('semester')->first();
        $today = Carbon::now()->toDateString();

        // if($semester_aktif->tgl_mulai_pengajuan_cuti && $semester_aktif->tgl_selesai_pengajuan_cuti){
        //     if($today < $semester_aktif->tgl_mulai_pengajuan_cuti || $today > $semester_aktif->tgl_selesai_pengajuan_cuti ){
        //     // return redirect()->back()->with('error', 'Periode Pengajuan Cuti telah berakhir!');
        //     return redirect()->route('mahasiswa.dashboard')->with('error', 'Periode Pengajuan Cuti telah berakhir!');
        //     }
        // }
        // dd($data);

        return view('mahasiswa.wisuda.store', ['data' => $data, 'semester_aktif' => $semester_aktif]);
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
        return redirect()->route('mahasiswa.wisuda.index')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function registrasi_ijazah(Request $request)
    {
        return view('bak.wisuda.registrasi-ijazah.index');
    }
}
