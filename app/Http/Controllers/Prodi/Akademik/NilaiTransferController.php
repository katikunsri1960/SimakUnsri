<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\MatkulKurikulum;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Referensi\AllPt;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class NilaiTransferController extends Controller
{
    public function index()
    {
        $semesterAktif = SemesterAktif::first();
        $semester = $semesterAktif->id_semester;
        $db = new RiwayatPendidikan;
        $data = $db->nilai_transfer_pendidikan(auth()->user()->fk_id, $semester);
        // dd($data);

        return view('prodi.data-akademik.nilai-transfer.index', ['data' => $data]);
    }

    public function get_matkul(Request $request, $nim)
    {
        $search = $request->get('q');

        $data_kurikulum = RiwayatPendidikan::where('nim', $nim)->first();

        $query = MatkulKurikulum::where('id_kurikulum', $data_kurikulum->id_kurikulum)
            ->orderby('nama_mata_kuliah', 'asc');
        if ($search) {
            $query->where('nama_mata_kuliah', 'like', "%{$search}%")
                ->orWhere('kode_mata_kuliah', 'like', "%{$search}%")
                ->where('id_kurikulum', $data_kurikulum->id_kurikulum);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function get_all_pt(Request $request)
    {
        $search = $request->get('q');
        $query = AllPt::orderby('nama_perguruan_tinggi', 'asc');

        if ($search) {
            $query->where('kode_perguruan_tinggi', 'like', "%{$search}%")
                ->orWhere('nama_perguruan_tinggi', 'like', "%{$search}%");
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function nilai_transfer($id_reg)
    {
        $semesterAktif = SemesterAktif::first();
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();
        $nilai_transfer = NilaiTransferPendidikan::with(['all_pt'])->where('id_registrasi_mahasiswa', $id_reg)->whereNull('id_aktivitas')->get();
        // dd($nilai_konversi);

        return view('prodi.data-akademik.nilai-transfer.input-nilai', [
            'd' => $data,
            'pengisian_nilai' => $semesterAktif,
            'transfer' => $nilai_transfer,
        ]);
    }

    public function store_nilai_transfer($id_reg, Request $request)
    {
        $semester = SemesterAktif::with('semester')->first();

        $mahasiswa = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

        $nilai_transfer = NilaiTransferPendidikan::with(['all_pt'])->where('id_registrasi_mahasiswa', $id_reg)->whereNull('id_aktivitas')->get();

        if ($semester->id_semester != $mahasiswa->id_periode_masuk) {
            return redirect()->back()->with('error', 'Masa Pengisian Nilai Telah Berakhir.');
        }

        $data = $request->validate([
            'asal_pt.*' => 'required',
            'kode_mata_kuliah_asal.*' => 'required',
            'nama_mata_kuliah_asal.*' => 'required',
            'sks_matkul_asal.*' => 'required',
            'nilai_huruf_asal.*' => 'required',
            'mata_kuliah_transfer.*' => 'required',
            'nilai_huruf_transfer.*' => 'required',

        ]);

        // Count jumlah dosen pengajar kelas kuliah
        $jumlah_matkul = count($request->mata_kuliah_transfer);

        $jumlah_sks_matkul = 0;

        for ($j = 0; $j < $jumlah_matkul; $j++) {

            $matkul = MataKuliah::where('id_matkul', $request->mata_kuliah_transfer[$j])->first();
            // dd($matkul);

            $jumlah_sks_matkul = $jumlah_sks_matkul + $matkul->sks_mata_kuliah;
        }

        if ($jumlah_sks_matkul == 0) {
            return redirect()->back()->with('error', 'SKS Mata Kuliah tidak boleh 0.');
        }

        try {
            DB::beginTransaction();

            for ($i = 0; $i < $jumlah_matkul; $i++) {
                // Generate id aktivitas mengajar
                $id_transfer = Uuid::uuid4()->toString();

                $matkul = MataKuliah::where('id_matkul', $request->mata_kuliah_transfer[$i])->first();

                $jumlah_sks = $matkul->sks_mata_kuliah;

                if ($request->nilai_huruf_transfer[$i] == 'A') {
                    $nilai_indeks = '4.00';
                } elseif ($request->nilai_huruf_transfer[$i] == 'B') {
                    $nilai_indeks = '3.00';
                } elseif ($request->nilai_huruf_transfer[$i] == 'C') {
                    $nilai_indeks = '2.00';
                } elseif ($request->nilai_huruf_transfer[$i] == 'D') {
                    $nilai_indeks = '1.00';
                } elseif ($request->nilai_huruf_transfer[$i] == 'E') {
                    $nilai_indeks = '0.00';
                } else {
                    return redirect()->back()->with('error', 'Nilai di luar range skala nilai.');
                }

                // Store data
                NilaiTransferPendidikan::create([
                    'feeder' => 0,
                    'id_transfer' => $id_transfer,
                    'id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa,
                    'nim' => $mahasiswa->nim,
                    'nama_mahasiswa' => $mahasiswa->nama_mahasiswa,
                    'id_prodi' => $mahasiswa->id_prodi,
                    'nama_program_studi' => $mahasiswa->nama_program_studi, 'id_periode_masuk' => $mahasiswa->id_periode_masuk, 'kode_mata_kuliah_asal' => $request->kode_mata_kuliah_asal[$i], 'nama_mata_kuliah_asal' => $request->nama_mata_kuliah_asal[$i], 'sks_mata_kuliah_asal' => $request->sks_matkul_asal[$i], 'nilai_huruf_asal' => $request->nilai_huruf_asal[$i], 'id_matkul' => $matkul->id_matkul, 'kode_matkul_diakui' => $matkul->kode_mata_kuliah, 'nama_mata_kuliah_diakui' => $matkul->nama_mata_kuliah, 'sks_mata_kuliah_diakui' => $matkul->sks_mata_kuliah, 'nilai_huruf_diakui' => $request->nilai_huruf_transfer[$i], 'nilai_angka_diakui' => $nilai_indeks, 'id_perguruan_tinggi' => $request->asal_pt[$i], 'id_semester' => $semester->id_semester, 'nama_semester' => $semester->semester->nama_semester, 'status_sync' => 'belum sync']);

            }

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    public function delete_nilai_transfer($transfer)
    {
        // dd($id_matkul);
        $nilai_transfer = NilaiTransferPendidikan::where('id_transfer', $transfer)->first();

        if ($nilai_transfer->feeder == 1) {
            return redirect()->back()->with('error', 'Data Nilai tidak bisa dihapus karena sudah di sinkronisasi');
        }

        try {
            DB::beginTransaction();

            NilaiTransferPendidikan::where('id_transfer', $transfer)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data Nilai Berhasil di Hapus!!');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Nilai Gagal di Hapus. ');
        }
    }
}
