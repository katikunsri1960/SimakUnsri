<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use App\Http\Controllers\Controller;
use App\Models\KuisonerAnswer;
use App\Models\KuisonerQuestion;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiController extends Controller
{
    public function index()
    {
        $id_reg_mhs = auth()->user()->fk_id;

        $jobData = DB::table('job_batches')->where('name', 'transkrip-mahasiswa')->where('pending_jobs', '>', 0)->first();

        $statusSync = $jobData ? 1 : 0;

        $id_batch = $jobData ? $jobData->id : null;

        // dd($jobData);
        $aktivitas_kuliah = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg_mhs)->orderBy('id_semester', 'desc')->get();
        $nilai_transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->orderBy('id_semester', 'asc')->get();
        $nilai_konversi = KonversiAktivitas::leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_anggota', 'konversi_aktivitas.id_anggota')
            ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'konversi_aktivitas.id_matkul')
            ->where('id_registrasi_mahasiswa', $id_reg_mhs)
            ->orderBy('id_semester', 'asc')
            ->get();

        $transkrip_mahasiswa = TranskripMahasiswa::where('id_registrasi_mahasiswa', $id_reg_mhs)->orderBy('nama_mata_kuliah', 'asc')->get();

        $total_sks_transkrip = $transkrip_mahasiswa->whereNotNull('nilai_indeks')->sum('sks_mata_kuliah');

        $bobot = 0;

        foreach ($transkrip_mahasiswa as $t) {
            $bobot += $t->nilai_indeks * $t->sks_mata_kuliah;
        }

        if ($total_sks_transkrip != 0) {
            $ipk = number_format($bobot / $total_sks_transkrip, 2);
        } else {
            $ipk = 0;
        }
        // dd($transkrip_mahasiswa,$total_sks_transkrip,$total_sks_transkrip, $ipk, $bobot);
        // dd($aktivitas_kuliah);

        return view('mahasiswa.nilai-perkuliahan.index', ['data_aktivitas' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa, 'nilai_konversi' => $nilai_konversi, 'nilai_transfer' => $nilai_transfer, 'total_sks_transkrip' => $total_sks_transkrip, 'bobot' => $bobot, 'ipk' => $ipk, 'statusSync' => $statusSync, 'id_batch' => $id_batch]);
    }

    public function lihat_khs($id_semester)
    {
        $id_reg_mhs = auth()->user()->fk_id;

        $aktivitas_kuliah = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg_mhs)->where('id_semester', $id_semester)->get();

        $nilai_mahasiswa = NilaiPerkuliahan::with(['dosen_pengajar', 'dosen_pengajar.dosen', 'kelas_kuliah' => function ($query) use ($id_reg_mhs) {
            $query->withCount(['kuisoner as q_count_c' => function ($query) use ($id_reg_mhs) {
                $query->where('id_registrasi_mahasiswa', $id_reg_mhs);
            }]);
        }])
            ->where('id_registrasi_mahasiswa', $id_reg_mhs)
            ->where('id_semester', $id_semester)
            ->orderBy('nama_mata_kuliah', 'asc')->get();

        $transkrip_mahasiswa = TranskripMahasiswa::where('id_registrasi_mahasiswa', $id_reg_mhs)->orderBy('nama_mata_kuliah', 'asc')->get();
        $nilai_transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->where('id_semester', $id_semester)->orderBy('id_semester', 'asc')->get();
        $nilai_konversi = KonversiAktivitas::with('aktivitas_mahasiswa', 'aktivitas_mahasiswa.bimbing_mahasiswa')
            ->leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_anggota', 'konversi_aktivitas.id_anggota')
            ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'konversi_aktivitas.id_matkul')
            ->where('id_registrasi_mahasiswa', $id_reg_mhs)
            ->where('id_semester', $id_semester)
            ->orderBy('id_semester', 'asc')
            ->get();
        // dd($nilai_konversi);
        $kuisoner = KuisonerQuestion::all();
        $count_kuisoner = $kuisoner->count();
        $semester_aktif = SemesterAktif::first()->id_semester;

        // dd($transkrip_mahasiswa);
        return view('mahasiswa.nilai-perkuliahan.include.detail-khs', [
            'data_nilai' => $nilai_mahasiswa,
            'data_aktivitas' => $aktivitas_kuliah,
            'transkrip' => $transkrip_mahasiswa,
            'nilai_konversi' => $nilai_konversi,
            'nilai_transfer' => $nilai_transfer,
            'kuisoner' => $kuisoner,
            'count_kuisoner' => $count_kuisoner,
            'semester_aktif' => $semester_aktif,
        ]);
    }

    public function histori_nilai($id_matkul)
    {
        $id_reg_mhs = auth()->user()->fk_id;
        $aktivitas_kuliah = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg_mhs)->orderBy('id_semester', 'desc')->get();
        $transkrip_mahasiswa = TranskripMahasiswa::where('id_registrasi_mahasiswa', $id_reg_mhs)->where('id_matkul', $id_matkul)->orderBy('nama_mata_kuliah', 'asc')->get();
        $nilai_transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->where('id_matkul', $id_matkul)->orderBy('id_semester', 'asc')->get();
        $nilai_konversi = KonversiAktivitas::leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_anggota', 'konversi_aktivitas.id_anggota')
            ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'konversi_aktivitas.id_matkul')
            ->where('id_registrasi_mahasiswa', $id_reg_mhs)
            ->where('konversi_aktivitas.id_matkul', $id_matkul)
            ->orderBy('id_semester', 'asc')
            ->get();
        // dd($dosen_pengajar[0]);

        return view('mahasiswa.nilai-perkuliahan.include.histori-nilai', ['data_aktivitas' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa, 'nilai_konversi' => $nilai_konversi, 'nilai_transfer' => $nilai_transfer]);
    }

    public function kuisioner(string $kelas)
    {
        $data = KuisonerQuestion::all();
        $kelas = KelasKuliah::with(['matkul', 'semester'])->where('id_kelas_kuliah', $kelas)->first();

        return view('mahasiswa.nilai-perkuliahan.include.kuisoner', ['data' => $data, 'kelas' => $kelas]);
    }

    public function kuisioner_store(Request $request, string $kelas)
    {
        $data = $request->all();
        $id_reg = auth()->user()->fk_id;

        $validator = Validator::make($data, [
            'nilai.*' => 'required|in:1,2,3,4,5,6,7', // Validasi nilai radio button harus 1, 2, 3, atau 4
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        foreach ($data['nilai'] as $kuisoner_question_id => $nilai) {
            KuisonerAnswer::create([
                'kuisoner_question_id' => $kuisoner_question_id,
                'id_kelas_kuliah' => $kelas,
                'id_registrasi_mahasiswa' => $id_reg,
                'nilai' => $nilai,
            ]);
        }
        $semester = KelasKuliah::where('id_kelas_kuliah', $kelas)->first()->id_semester;

        return redirect()->route('mahasiswa.perkuliahan.nilai-perkuliahan.lihat-khs', $semester)->with('success', 'Kuisoner berhasil diisi');
    }
}
