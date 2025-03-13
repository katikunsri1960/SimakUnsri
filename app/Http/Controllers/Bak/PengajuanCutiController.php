<?php

namespace App\Http\Controllers\Bak;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class PengajuanCutiController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester',
        ]);

        $data = $db->with(['riwayat', 'prodi']);

        $pilihan_semester = Semester::select('id_semester', 'nama_semester')->orderBy('id_semester', 'desc')->get();
        $semester_view = $request->semester_view ?? SemesterAktif::select('id_semester')->first()->id_semester;

        $prodi = ProgramStudi::all();

        $data = $data->where('id_semester', $semester_view)
                ->get();

        return view('bak.pengajuan-cuti.index',[
            'data' => $data,
            'pilihan_semester' => $pilihan_semester,
            'semester_view' => $semester_view,
        ]);
    }

    public function cuti_approve(PengajuanCuti $cuti)
    {
        if($cuti->approved < 1){
            return redirect()->back()->with('error', 'Pengajuan Cuti belum disetujui Fakultas');
        }

        // dd($cuti);
        $store = $cuti->update([
            'approved' => 2,
            'alasan_pembatalan' => NULL
        ]);

        $akm_terakhir = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $cuti->id_registrasi_mahasiswa)->orderBy('id_semester', 'desc')->first();

        // dd($akm_terakhir);

        AktivitasKuliahMahasiswa::updateOrCreate(
            [
                'id_semester' => $cuti->id_semester,
                'id_registrasi_mahasiswa' => $cuti->id_registrasi_mahasiswa,
            ],
            [
                'feeder' => 0,
                'id_registrasi_mahasiswa' => $cuti->id_registrasi_mahasiswa,
                'nim' => $cuti->nim,
                'nama_mahasiswa' => $cuti->nama_mahasiswa,
                'id_prodi' => $cuti->id_prodi,
                'nama_program_studi' => $cuti->prodi->nama_program_studi,
                'angkatan' => $cuti->riwayat->angkatan,
                'id_periode_masuk' => $cuti->riwayat->id_periode_masuk,
                'id_semester' => $cuti->id_semester,
                'nama_semester' => $cuti->nama_semester,
                'id_status_mahasiswa' => 'C',
                'nama_status_mahasiswa' => 'Cuti',
                'ips' => '0.00',
                'ipk' => $akm_terakhir->ipk,
                'sks_semester' => 0,
                'sks_total' => $akm_terakhir->sks_total,
                'biaya_kuliah_smt' => 0,
                'id_pembiayaan' => 1,
                'status_sync' => 'belum sync',
            ]
        );

        return redirect()->back()->with('success', 'Pengajuan Cuti berhasil disimpan');
    }

    public function pembatalan_cuti(Request $request, $cuti)
    {
        $pengajuan_cuti = PengajuanCuti::where('id_cuti',$cuti)->first();
        
        PengajuanCuti::where('id_cuti',$cuti)->update([
            'approved' => 4,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        $akm= AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $pengajuan_cuti->id_registrasi_mahasiswa)
                ->where('id_semester', $pengajuan_cuti->id_semester)
                ->first();

        if($akm){
            try {
                DB::beginTransaction();
                $akm->delete();
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Gagal menghapus data Aktivitas Kuliah Mahasiswa. '.$th->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Pengajuan Cuti berhasil dibatalkan');
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
            return redirect()->route('bak.dashboard')->with('error', 'Periode Pengajuan Cuti telah berakhir!');
            }
        }
        // dd($data);

        return view('bak.pengajuan-cuti.store', ['data' => $data, 'semester_aktif' => $semester_aktif]);
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
        return redirect()->route('bak.pengajuan-cuti.index')->with('success', 'Data Berhasil di Tambahkan');
    }
}
