<?php

namespace App\Http\Controllers\Bak;

use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PengajuanCuti;
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

        $pilihan_semester = Semester::select('id_semester', 'nama_semester')->whereNot('semester', 3)->orderBy('id_semester', 'desc')->get();
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
}
