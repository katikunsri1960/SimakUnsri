<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\MatkulKurikulum;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Referensi\AllPt;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class AktivitasNonTAController extends Controller
{
    private function checkSemesterAllow($semester)
    {
        $semester_aktif = SemesterAktif::first();

        if ($semester != null && $semester_aktif->semester_allow != null && ! in_array($semester, $semester_aktif->semester_allow)) {
            return false;
        }

        return true;
    }

    public function index(Request $request)
    {
        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester',
        ]);

        $semester_view = $request->semester_view ?? null;

        $semester_aktif = SemesterAktif::first();

        $semester_pilih = $semester_view == null ? $semester_aktif->id_semester : $semester_view;

        if ($this->checkSemesterAllow($semester_pilih) == false) {
            return redirect()->back()->with('error', 'Semester Tidak dalam list yang di izinkan!');
        }

        $dbSemester = Semester::select('id_semester', 'nama_semester');

        $pilihan_semester = $semester_aktif->semester_allow != null ? $dbSemester->whereIn('id_semester', $semester_aktif->semester_allow)->orderBy('id_semester', 'desc')->get() : $dbSemester->whereIn('id_semester', [$semester_aktif->id_semester])->orderBy('id_semester', 'desc')->get();
        // dd($semester_allow)

        $db = new AktivitasMahasiswa;
        $data = $db->aktivitas_non_ta(auth()->user()->fk_id, $semester_pilih);

        // dd($data);
        return view('prodi.data-akademik.non-tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester_pilih,
            'pilihan_semester' => $pilihan_semester,
            'semester_view' => $semester_view,
            'semester_aktif' => $semester_aktif,
        ]);
    }

    public function approve_pembimbing(AktivitasMahasiswa $aktivitasMahasiswa)
    {
        $db = new AktivitasMahasiswa;
        $result = $db->approve_pembimbing($aktivitasMahasiswa->id_aktivitas);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function ubah_detail_non_tugas_akhir($aktivitas)
    {
        // $semesterAktif = SemesterAktif::first();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa'])->where('id_aktivitas', $aktivitas)->first();
        $semester = $data->id_semester;

        return view('prodi.data-akademik.non-tugas-akhir.edit', [
            'd' => $data,
            'semester' => $semester,
        ]);
    }

    public function update_detail_non_tugas_akhir($aktivitas, Request $request)
    {
        $data = $request->validate([
            'sk_tugas' => 'required',
            'tanggal_sk' => 'required',
            'bulan_sk' => 'required',
            'tahun_sk' => 'required',
        ]);
        // Generate tanggal sk tugas
        $tanggal_sk_tugas = $request->tahun_sk.'-'.$request->bulan_sk.'-'.$request->tanggal_sk;

        AktivitasMahasiswa::where('id_aktivitas', $aktivitas)->update(['sk_tugas' => $request->sk_tugas, 'tanggal_sk_tugas' => $tanggal_sk_tugas]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');
        // $prodi_id = auth()->user()->fk_id;
        $tahun_ajaran = SemesterAktif::with('semester')->first();
        // $tahun_ajaran = Semester::where('id_semester','=','20231')->where('a_periode_aktif','=','1')->get();

        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran)
            ->orderby('nama_dosen', 'asc');
        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                ->orWhere('nama_program_studi', 'like', "%{$search}%")
                ->where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran);
        }

        $data = $query->get();

        return response()->json($data);
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

    public function tambah_dosen_pembimbing($aktivitas)
    {
        $semesterAktif = SemesterAktif::first();
        $kategori = KategoriKegiatan::where('id_kategori_kegiatan', '110300')->get();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa'])->where('id_aktivitas', $aktivitas)->first();
        // dd($data);

        return view('prodi.data-akademik.non-tugas-akhir.tambah-dosen', [
            'd' => $data,
            'kategori' => $kategori,
        ]);
    }

    public function store_dosen_pembimbing($aktivitas, Request $request)
    {
        $semester_aktif = SemesterAktif::first();
        $data = $request->validate([
            'dosen_pembimbing.*' => 'required',
            'kategori.*' => 'required',
            'pembimbing_ke.*' => 'required|numeric|min:1',
        ]);
        try {
            DB::beginTransaction();

            $aktivitas_mahasiswa = AktivitasMahasiswa::where('id_aktivitas', $aktivitas)->first();
            $semester = Semester::where('id_semester', $aktivitas_mahasiswa->id_semester)->first();

            $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran)->whereIn('id_registrasi_dosen', $request->dosen_pembimbing)->get();

            if ($dosen_pembimbing->count() == 0 || $dosen_pembimbing->count() != count($request->dosen_pembimbing)) {
                $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran - 1)->whereIn('id_registrasi_dosen', $request->dosen_pembimbing)->get();
            }
            // Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen = count($request->dosen_pembimbing);

            for ($i = 0; $i < $jumlah_dosen; $i++) {
                // Generate id aktivitas mengajar
                $id_bimbing_mahasiswa = Uuid::uuid4()->toString();
                $dosen = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran)->where('id_registrasi_dosen', $request->dosen_pembimbing[$i])->first();
                if (! $dosen) {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran - 1)->where('id_registrasi_dosen', $request->dosen_pembimbing[$i])->first();
                }

                $kategori = KategoriKegiatan::where('id_kategori_kegiatan', $request->kategori[$i])->first();
                // dd($kategori);

                // Store data to table tanpa substansi kuliah
                BimbingMahasiswa::create(['feeder' => 0, 'approved' => 0, 'approved_dosen' => 0, 'id_bimbing_mahasiswa' => $id_bimbing_mahasiswa, 'id_aktivitas' => $aktivitas, 'judul' => $aktivitas_mahasiswa->judul, 'id_kategori_kegiatan' => $kategori->id_kategori_kegiatan, 'nama_kategori_kegiatan' => $kategori->nama_kategori_kegiatan, 'id_dosen' => $dosen->id_dosen, 'nidn' => $dosen->nidn, 'nama_dosen' => $dosen->nama_dosen, 'pembimbing_ke' => $request->pembimbing_ke[$i], 'status_sync' => 'belum sync']);

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.non-tugas-akhir.edit-detail', $aktivitas)->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    public function edit_dosen_pembimbing($bimbing)
    {
        $semesterAktif = SemesterAktif::first();
        $kategori = KategoriKegiatan::where('id_kategori_kegiatan', '110300')->get();
        $data = BimbingMahasiswa::with(['anggota_aktivitas_personal'])->where('id', $bimbing)->first();
        // dd($data);

        return view('prodi.data-akademik.tugas-akhir.edit-dosen', [
            'data' => $data,
            'kategori' => $kategori,
        ]);
    }

    public function update_dosen_pembimbing($bimbing, $aktivitas, Request $request)
    {
        // dd($aktivitas);
        $semester_aktif = SemesterAktif::first();
        $data = $request->validate([
            'dosen_pembimbing' => 'required',
            'kategori.*' => 'required',
            'pembimbing_ke.*' => 'required|numeric|min:1',
        ]);
        try {
            DB::beginTransaction();
            $aktivitas_mahasiswa = AktivitasMahasiswa::where('id_aktivitas', $aktivitas)->first();
            $semester = Semester::where('id_semester', $aktivitas_mahasiswa->id_semester)->first();
            $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran)->whereIn('id_dosen', $request->dosen_pembimbing)->get();

            if ($dosen_pembimbing->count() == 0 || $dosen_pembimbing->count() != count($request->dosen_pembimbing)) {
                $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran - 1)->whereIn('id_dosen', $request->dosen_pembimbing)->get();
            }
            // Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen = count($request->dosen_pembimbing);

            for ($i = 0; $i < $jumlah_dosen; $i++) {
                $dosen = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran)->where('id_dosen', $request->dosen_pembimbing[$i])->first();
                if (! $dosen) {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran - 1)->where('id_dosen', $request->dosen_pembimbing[$i])->first();
                }

                $kategori = KategoriKegiatan::where('id_kategori_kegiatan', $request->kategori[$i])->first();
                // dd($kategori);

                // Store data to table tanpa substansi kuliah
                BimbingMahasiswa::where('id', $bimbing)->update(['approved' => 0, 'approved_dosen' => 0, 'id_kategori_kegiatan' => $kategori->id_kategori_kegiatan, 'nama_kategori_kegiatan' => $kategori->nama_kategori_kegiatan, 'id_dosen' => $dosen->id_dosen, 'nidn' => $dosen->nidn, 'nama_dosen' => $dosen->nama_dosen, 'pembimbing_ke' => $request->pembimbing_ke[$i]]);

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.non-tugas-akhir.edit-detail', $aktivitas)->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    public function delete_dosen_pembimbing($bimbing)
    {
        // dd($aktivitas);
        try {
            $aktivitas = BimbingMahasiswa::findOrFail($bimbing);
            $aktivitas->delete();

            return redirect()->back()
                ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan.');
        }
    }

    public function nilai_konversi($aktivitas)
    {

        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa'])->where('id_aktivitas', $aktivitas)->first();
        $nilai_konversi = KonversiAktivitas::where('id_aktivitas', $aktivitas)->get();
        // dd($nilai_konversi);

        return view('prodi.data-akademik.non-tugas-akhir.nilai-konversi', [
            'd' => $data,
            'konversi' => $nilai_konversi,
        ]);
    }

    public function store_nilai_konversi($aktivitas, Request $request)
    {
        // $semester = SemesterAktif::with('semester')->first();

        $aktivitas_mahasiswa = AktivitasMahasiswa::with(['anggota_aktivitas_personal'])->where('id_aktivitas', $aktivitas)->first();

        $nilai_konversi = KonversiAktivitas::with(['matkul'])->where('id_aktivitas', $aktivitas)->get();

        $semester = Semester::where('id_semester', $aktivitas_mahasiswa->id_semester)->first();

        if ($this->checkSemesterAllow($aktivitas_mahasiswa->id_semester) == false) {
            return redirect()->back()->with('error', 'Masa Pengisian Nilai Telah Berakhir.');
        }

        // if(strtotime(date('Y-m-d')) < strtotime($semester->mulai_isi_nilai)){
        //     return redirect()->back()->with('error', 'Masa Pengisian Nilai Belum di Mulai.');
        // }

        // if(strtotime(date('Y-m-d')) > strtotime($semester->batas_isi_nilai)){
        //     return redirect()->back()->with('error', 'Masa Pengisian Nilai Telah Berakhir.');
        // }

        if (! $aktivitas_mahasiswa || ! $aktivitas_mahasiswa->sk_tugas) {
            return redirect()->back()->with('error', 'SK Aktivitas Belum di Isi.');
        }

        // dd($nilai_konversi);

        $data = $request->validate([
            'mata_kuliah.*' => 'required',
            'nilai_angka.*' => 'required',
        ]);

        // Count jumlah dosen pengajar kelas kuliah
        $jumlah_matkul = count($request->mata_kuliah);

        $jumlah_sks_matkul = 0;

        for ($j = 0; $j < $jumlah_matkul; $j++) {

            $matkul = MataKuliah::where('id_matkul', $request->mata_kuliah[$j])->first();
            // dd($matkul);

            $jumlah_sks_matkul = $jumlah_sks_matkul + $matkul->sks_mata_kuliah;
        }

        if ($jumlah_sks_matkul == 0) {
            return redirect()->back()->with('error', 'SKS Mata Kuliah tidak boleh 0.');
        }

        if (! $nilai_konversi) {

            if ($jumlah_sks_matkul > $aktivitas_mahasiswa->sks_aktivitas) {
                return redirect()->back()->with('error', 'SKS Mata Kuliah Melebihi Batas SKS Konversi Aktivitas.');
            }

        } else {

            $jumlah_sks_nilai_konversi = $nilai_konversi->sum('sks_mata_kuliah');
            $total_sks = $jumlah_sks_matkul + $jumlah_sks_nilai_konversi;

            if ($total_sks > $aktivitas_mahasiswa->sks_aktivitas) {
                return redirect()->back()->with('error', 'SKS Mata Kuliah Melebihi Batas SKS Konversi Aktivitas.');
            }
        }

        try {
            DB::beginTransaction();

            for ($i = 0; $i < $jumlah_matkul; $i++) {
                // Generate id aktivitas mengajar
                $id_konversi_aktivitas = Uuid::uuid4()->toString();

                $matkul = MataKuliah::where('id_matkul', $request->mata_kuliah[$i])->first();

                $jumlah_sks = $matkul->sks_mata_kuliah;

                if ($request->nilai_angka[$i] > 100) {
                    $nilai_akhir_sidang = 100;
                }

                if ($request->nilai_angka[$i] >= 86 && $request->nilai_angka[$i] <= 100) {
                    $nilai_indeks = '4.00';
                    $nilai_huruf = 'A';
                } elseif ($request->nilai_angka[$i] >= 71 && $request->nilai_angka[$i] < 86) {
                    $nilai_indeks = '3.00';
                    $nilai_huruf = 'B';
                } elseif ($request->nilai_angka[$i] >= 56 && $request->nilai_angka[$i] < 71) {
                    $nilai_indeks = '2.00';
                    $nilai_huruf = 'C';
                } elseif ($request->nilai_angka[$i] >= 41 && $request->nilai_angka[$i] < 56) {
                    $nilai_indeks = '1.00';
                    $nilai_huruf = 'D';
                } elseif ($request->nilai_angka[$i] >= 0 && $request->nilai_angka[$i] < 41) {
                    $nilai_indeks = '0.00';
                    $nilai_huruf = 'E';
                } else {
                    return redirect()->back()->with('error', 'Nilai di luar range skala nilai.');
                }

                // Store data to table tanpa substansi kuliah
                KonversiAktivitas::create(['feeder' => 0, 'id_konversi_aktivitas' => $id_konversi_aktivitas, 'id_matkul' => $matkul->id_matkul, 'nama_mata_kuliah' => $matkul->nama_mata_kuliah, 'id_aktivitas' => $aktivitas_mahasiswa->id_aktivitas, 'judul' => $aktivitas_mahasiswa->judul, 'id_anggota' => $aktivitas_mahasiswa->anggota_aktivitas_personal->id_anggota, 'nama_mahasiswa' => $aktivitas_mahasiswa->anggota_aktivitas_personal->nama_mahasiswa, 'nim' => $aktivitas_mahasiswa->anggota_aktivitas_personal->nim, 'sks_mata_kuliah' => $matkul->sks_mata_kuliah, 'nilai_angka' => $request->nilai_angka[$i], 'nilai_indeks' => $nilai_indeks, 'nilai_huruf' => $nilai_huruf, 'id_semester' => $semester->id_semester, 'nama_semester' => $semester->nama_semester, 'status_sync' => 'belum sync']);

            }

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    public function delete_nilai_konversi($konversi)
    {
        // dd($id_matkul);
        $nilai_konversi = KonversiAktivitas::where('id_konversi_aktivitas', $konversi)->first();

        if ($nilai_konversi->feeder == 1) {
            return redirect()->back()->with('error', 'Data Nilai tidak bisa dihapus karena sudah di sinkronisasi');
        }

        try {
            DB::beginTransaction();

            KonversiAktivitas::where('id_konversi_aktivitas', $konversi)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data Nilai Berhasil di Hapus!!');
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data Nilai Gagal di Hapus. ');
        }
    }

    public function nilai_transfer($aktivitas)
    {
        $semesterAktif = SemesterAktif::first();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa'])->where('id_aktivitas', $aktivitas)->first();
        $nilai_transfer = NilaiTransferPendidikan::with(['all_pt'])->where('id_aktivitas', $aktivitas)->get();
        // dd($nilai_konversi);

        return view('prodi.data-akademik.non-tugas-akhir.nilai-transfer', [
            'd' => $data,
            'pengisian_nilai' => $semesterAktif,
            'transfer' => $nilai_transfer,
        ]);
    }

    public function store_nilai_transfer($aktivitas, Request $request)
    {
        // $semester = SemesterAktif::with('semester')->first();

        $aktivitas_mahasiswa = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa'])->where('id_aktivitas', $aktivitas)->first();

        if ($this->checkSemesterAllow($aktivitas_mahasiswa->id_semester) == false) {
            return redirect()->back()->with('error', 'Masa Pengisian Nilai Telah Berakhir.');
        }

        $nilai_transfer = NilaiTransferPendidikan::with(['matkul'])->where('id_aktivitas', $aktivitas)->get();

        // if($semester->id_semester != $aktivitas_mahasiswa->id_semester){
        //     return redirect()->back()->with('error', 'Masa Pengisian Nilai Telah Berakhir.');
        // }

        // if(strtotime(date('Y-m-d')) < strtotime($semester->mulai_isi_nilai)){
        //     return redirect()->back()->with('error', 'Masa Pengisian Nilai Belum di Mulai.');
        // }

        // if(strtotime(date('Y-m-d')) > strtotime($semester->batas_isi_nilai)){
        //     return redirect()->back()->with('error', 'Masa Pengisian Nilai Telah Berakhir.');
        // }

        if (! $aktivitas_mahasiswa || ! $aktivitas_mahasiswa->sk_tugas) {
            return redirect()->back()->with('error', 'SK Aktivitas Belum di Isi.');
        }

        // dd($aktivitas_mahasiswa->anggota_aktivitas_personal);

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
                    'id_registrasi_mahasiswa' => $aktivitas_mahasiswa->anggota_aktivitas_personal->mahasiswa->id_registrasi_mahasiswa,
                    'nim' => $aktivitas_mahasiswa->anggota_aktivitas_personal->mahasiswa->nim,
                    'nama_mahasiswa' => $aktivitas_mahasiswa->anggota_aktivitas_personal->mahasiswa->nama_mahasiswa,
                    'id_prodi' => $aktivitas_mahasiswa->anggota_aktivitas_personal->mahasiswa->id_prodi,
                    'nama_program_studi' => $aktivitas_mahasiswa->anggota_aktivitas_personal->mahasiswa->nama_program_studi, 'id_periode_masuk' => $aktivitas_mahasiswa->anggota_aktivitas_personal->mahasiswa->id_periode_masuk, 'kode_mata_kuliah_asal' => $request->kode_mata_kuliah_asal[$i], 'nama_mata_kuliah_asal' => $request->nama_mata_kuliah_asal[$i], 'sks_mata_kuliah_asal' => $request->sks_matkul_asal[$i], 'nilai_huruf_asal' => $request->nilai_huruf_asal[$i], 'id_matkul' => $matkul->id_matkul, 'kode_matkul_diakui' => $matkul->kode_mata_kuliah, 'nama_mata_kuliah_diakui' => $matkul->nama_mata_kuliah, 'sks_mata_kuliah_diakui' => $matkul->sks_mata_kuliah, 'nilai_huruf_diakui' => $request->nilai_huruf_transfer[$i], 'nilai_angka_diakui' => $nilai_indeks, 'id_perguruan_tinggi' => $request->asal_pt[$i], 'id_aktivitas' => $aktivitas_mahasiswa->id_aktivitas, 'judul' => $aktivitas_mahasiswa->judul, 'id_jenis_aktivitas' => $aktivitas_mahasiswa->id_jenis_aktivitas, 'nama_jenis_aktivitas' => $aktivitas_mahasiswa->nama_jenis_aktivitas, 'id_semester' => $aktivitas_mahasiswa->id_semester, 'nama_semester' => $aktivitas_mahasiswa->nama_semester, 'status_sync' => 'belum sync']);

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
