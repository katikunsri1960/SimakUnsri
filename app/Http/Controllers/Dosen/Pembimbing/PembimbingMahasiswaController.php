<?php

namespace App\Http\Controllers\Dosen\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembimbingMahasiswaController extends Controller
{
    public function bimbingan_akademik()
    {
        $semester = SemesterAktif::with(['semester'])->first();

        $data = RiwayatPendidikan::with(['aktivitas_kuliah', 'prodi', 'peserta_kelas'])
                    ->withCount(['peserta_kelas' => function($query) use ($semester) {
                        $query->whereHas('kelas_kuliah', function($query) use ($semester) {
                            $query->where('id_semester', $semester->id_semester)
                                ->where('approved', 0);
                        });
                    }])
                    // $query->where('id_semester', $semester->id_semester);
                    ->whereHas('aktivitas_kuliah', function($query) use ($semester) {
                        $query->where('id_semester', $semester->id_semester);
                    })->where('dosen_pa', auth()->user()->fk_id)
                ->get();

        return view('dosen.pembimbing.akademik.index', [
            'data' => $data,
            'semester' => $semester,
        ]);
    }

    public function bimbingan_akademik_detail(RiwayatPendidikan $riwayat)
    {
        $id = $riwayat->id_registrasi_mahasiswa;
        $semester = SemesterAktif::first()->id_semester;
        $data = PesertaKelasKuliah::with(['kelas_kuliah', 'kelas_kuliah.matkul'])
                ->whereHas('kelas_kuliah', function($query) use ($semester) {
                    $query->where('id_semester', $semester);
                })
                ->where('id_registrasi_mahasiswa', $id)
                ->orderBy('kode_mata_kuliah')
                ->get();

        // dd($data);

        return view('dosen.pembimbing.akademik.detail', [
            'riwayat' => $riwayat,
            'data' => $data,
        ]);
    }

    public function bimbingan_akademik_approve_all(RiwayatPendidikan $riwayat)
    {
        $id = $riwayat->id_registrasi_mahasiswa;

        $db = new PesertaKelasKuliah();

        $store = $db->approve_all($id);
        // dd($store);
        return redirect()->back()->with($store['status'], $store['message']);
    }

    public function bimbingan_non_akademik()
    {
        return view('dosen.pembimbing.bimbingan-non-akademik');
    }

    public function bimbingan_tugas_akhir(Request $request)
    {
        if ($request->has('semester') && $request->semester != '') {
            $id_semester = $request->semester;
        } else {
            $id_semester = SemesterAktif::first()->id_semester;
        }

        $db = new AktivitasMahasiswa();
        $data = $db->bimbing_ta(auth()->user()->fk_id, $id_semester);

        $semester = Semester::orderBy('id_semester', 'desc')->get();
        // dd($data);
        return view('dosen.pembimbing.tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester,
            'id_semester' => $id_semester,
        ]);
    }

    public function approve_pembimbing(AktivitasMahasiswa $aktivitas)
    {
        // dd($aktivitas);
        $id_dosen = auth()->user()->fk_id;
        $aktivitas->bimbing_mahasiswa()->where('id_dosen', $id_dosen)->update([
            'approved_dosen' => 1,
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
