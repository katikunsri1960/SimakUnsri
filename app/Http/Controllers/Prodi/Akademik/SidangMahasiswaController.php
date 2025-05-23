<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\UjiMahasiswa;
use App\Models\Perkuliahan\NilaiSidangMahasiswa;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SidangMahasiswaController extends Controller
{
    private function checkSemesterAllow($semester)
    {
        $semester_aktif = SemesterAktif::first();

        if ($semester != null && $semester_aktif->semester_allow != null && !in_array($semester,$semester_aktif->semester_allow)) {
            return false;
        }
        return true;
    }

    public function index(Request $request)
    {
        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester'
        ]);

        $semester_view = $request->semester_view ?? null;

        $semester_aktif = SemesterAktif::first();

        $semester_pilih = $semester_view == null ? $semester_aktif->id_semester : $semester_view;

        if ($this->checkSemesterAllow($semester_pilih) == false) {
            return redirect()->back()->with('error', "Semester Tidak dalam list yang di izinkan!");
        }

        $dbSemester = Semester::select('id_semester', 'nama_semester');

        $pilihan_semester = $semester_aktif->semester_allow != null ? $dbSemester->whereIn('id_semester', $semester_aktif->semester_allow)->orderBy('id_semester', 'desc')->get() : $dbSemester->whereIn('id_semester', [$semester_aktif->id_semester])->orderBy('id_semester', 'desc')->get();
        // dd($semester_allow)

        $db = new AktivitasMahasiswa();
        $data = $db->sidang(auth()->user()->fk_id, $semester_pilih );
        // dd($data);
        return view('prodi.data-akademik.sidang-mahasiswa.index', [
            'data' => $data,
            'semester' => $semester_pilih,
            'pilihan_semester' => $pilihan_semester,
            'semester_view' => $semester_view,
            'semester_aktif' => $semester_aktif
        ]);
    }

    public function approve_penguji(AktivitasMahasiswa $aktivitasMahasiswa)
    {
        $db = new AktivitasMahasiswa();
        $result = $db->approve_penguji($aktivitasMahasiswa->id_aktivitas);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function ubah_detail_sidang($aktivitas)
    {

        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'uji_mahasiswa'])->where('id_aktivitas', $aktivitas)->first();
        // dd($data);
        $semester = $data->id_semester;

        return view('prodi.data-akademik.sidang-mahasiswa.edit', [
            'd' => $data,
            'semester' => $semester
        ]);
    }

    public function update_detail_sidang($aktivitas, Request $request)
    {
        $detik = "00";
        $tahun_aktif = date('Y');

        $data = $request->validate([
                    'tanggal_ujian' => 'required',
                    'bulan_ujian' => 'required',
                    'jam_mulai' => 'required',
                    'menit_mulai' => 'required',
                    'jam_selesai' => 'required',
                    'menit_selesai' => 'required'
                ]);
        //Generate tanggal ujian
        $tanggal_ujian = $tahun_aktif."-".$request->bulan_ujian."-".$request->tanggal_ujian;

        //Generate jam pelaksanaan
        $jam_mulai_sidang = $request->jam_mulai.":".$request->menit_mulai.":".$detik;
        $jam_selesai_sidang = $request->jam_selesai.":".$request->menit_selesai.":".$detik;

        AktivitasMahasiswa::where('id_aktivitas', $aktivitas)->update(['tanggal_selesai' => $tanggal_ujian, 'jadwal_ujian' => $tanggal_ujian, 'jadwal_jam_mulai' => $jam_mulai_sidang, 'jadwal_jam_selesai' => $jam_selesai_sidang]);

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

    public function tambah_dosen_penguji($aktivitas)
    {
        // $semesterAktif = SemesterAktif::first();
        $kategori = KategoriKegiatan::whereIn('id_kategori_kegiatan', ['110501', '110502'])->get();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'uji_mahasiswa'])->where('id_aktivitas', $aktivitas)->first();
        $semester = $data->id_semester;
        // dd($data);

        return view('prodi.data-akademik.sidang-mahasiswa.tambah-dosen', [
            'd' => $data,
            'kategori' => $kategori,
            'semester' => $semester
        ]);
    }

    public function store_dosen_penguji($aktivitas, Request $request)
    {
        $semester_aktif = SemesterAktif::first();
        $bimbingMahasiswa = BimbingMahasiswa::where('id_aktivitas', $aktivitas)->get();

        $data = $request->validate([
                    'dosen_penguji.*' => 'required',
                    'kategori.*' => 'required',
                    'penguji_ke.*' => 'required|numeric|min:1',
                ]);
        try {
            DB::beginTransaction();

            $aktivitas_mahasiswa = AktivitasMahasiswa::where('id_aktivitas', $aktivitas)->first();
            $semester = Semester::where('id_semester', $aktivitas_mahasiswa->id_semester)->first();

            $dosen_penguji = PenugasanDosen::where('id_tahun_ajaran', $semester->id_tahun_ajaran)->whereIn('id_registrasi_dosen', $request->dosen_penguji)->get();

            if($dosen_penguji->count() == 0 || $dosen_penguji->count() != count($request->dosen_penguji)){
                $dosen_penguji = PenugasanDosen::where('id_tahun_ajaran',$semester->id_tahun_ajaran-1)->whereIn('id_registrasi_dosen', $request->dosen_penguji)->get();
            }
            //Count jumlah dosen penguji
            $jumlah_dosen=count($request->dosen_penguji);

            for($i=0;$i<$jumlah_dosen;$i++){
                //Generate id uji mahasiswa
                $id_uji_mahasiswa = Uuid::uuid4()->toString();
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester->id_tahun_ajaran)->where('id_registrasi_dosen', $request->dosen_penguji[$i])->first();

                if(!$dosen)
                {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester->id_tahun_ajaran-1)->where('id_registrasi_dosen', $request->dosen_penguji[$i])->first();
                }

                $pembimbing = $bimbingMahasiswa->where('id_dosen', $dosen->id_dosen)->first();

                if($pembimbing){
                    return redirect()->back()->with('error', 'Dosen Pembimbing Tidak Bisa Menjadi Penguji.');
                }

                $kategori = KategoriKegiatan::where('id_kategori_kegiatan', $request->kategori[$i])->first();
                // dd($kategori);

                $kategori_kegiatan = $request->penguji_ke[$i] == '1' ? '110501' : '110502';
                $nama_kategori_kegiatan = $request->penguji_ke[$i] == '1' ? 'Ketua Penguji' : 'Anggota Penguji';

                //Store data to table tanpa substansi kuliah
                UjiMahasiswa::create([
                    'feeder' => 0,
                    'id_uji' => $id_uji_mahasiswa,
                    'id_aktivitas' => $aktivitas,
                    'judul' => $aktivitas_mahasiswa->judul,
                    'id_kategori_kegiatan' => $kategori_kegiatan,
                    'nama_kategori_kegiatan' => $nama_kategori_kegiatan,
                    'id_dosen' => $dosen->id_dosen,
                    'nidn' => $dosen->nidn,
                    'nama_dosen' => $dosen->nama_dosen,
                    'penguji_ke' => $request->penguji_ke[$i],
                    'status_uji_mahasiswa' => 0,
                    'status_sync' => 'Belum Sync'
                ]);

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.sidang-mahasiswa.edit-detail', $aktivitas)->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    public function edit_dosen_penguji($uji)
    {
        $semesterAktif = SemesterAktif::first();
        $kategori = KategoriKegiatan::whereIn('id_kategori_kegiatan', ['110501','110502'])->get();
        $data = UjiMahasiswa::with(['anggota_aktivitas_personal'])->where('id', $uji)->first();
        // dd($data);

        return view('prodi.data-akademik.sidang-mahasiswa.edit-dosen', [
            'data' => $data,
            'kategori' => $kategori
        ]);
    }

    public function update_dosen_penguji($uji, $aktivitas, Request $request)
    {
        // dd($aktivitas);
        $semester_aktif = SemesterAktif::first();
        $bimbingMahasiswa = BimbingMahasiswa::where('id_aktivitas', $aktivitas)->get();

        $data = $request->validate([
                    'dosen_penguji' => 'required',
                    'kategori.*' => 'required',
                    'penguji_ke.*' => 'required|numeric|min:1',
                ]);
        try {
            DB::beginTransaction();

            $dosen_penguji = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->whereIn('id_dosen', $request->dosen_penguji)->get();

            if($dosen_penguji->count() == 0 || $dosen_penguji->count() != count($request->dosen_penguji)){
                $dosen_penguji = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->whereIn('id_dosen', $request->dosen_penguji)->get();
            }
            //Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen=count($request->dosen_penguji);

            for($i=0;$i<$jumlah_dosen;$i++){
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->where('id_dosen', $request->dosen_penguji[$i])->first();
                if(!$dosen)
                {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->where('id_dosen', $request->dosen_penguji[$i])->first();
                }

                $pembimbing = $bimbingMahasiswa->where('id_dosen', $dosen->id_dosen)->first();

                if($pembimbing){
                    return redirect()->back()->with('error', 'Dosen Pembimbing Tidak Bisa Menjadi Penguji.');
                }

                $kategori = KategoriKegiatan::where('id_kategori_kegiatan', $request->kategori[$i])->first();
                // dd($kategori);

                //Store data to table tanpa substansi kuliah
                UjiMahasiswa::where('id', $uji)->update(['id_kategori_kegiatan' => $kategori->id_kategori_kegiatan, 'nama_kategori_kegiatan' => $kategori->nama_kategori_kegiatan, 'id_dosen'=> $dosen->id_dosen, 'nidn' => $dosen->nidn, 'nama_dosen' => $dosen->nama_dosen, 'penguji_ke' => $request->penguji_ke[$i], 'status_uji_mahasiswa' => 0]);

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.sidang-mahasiswa.edit-detail', $aktivitas)->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    public function delete_dosen_penguji($uji)
    {
        // dd($aktivitas);
        try {
            $aktivitas = UjiMahasiswa::findOrFail($uji);
            $aktivitas->delete();

            return redirect()->back()
                             ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan, tidak dapat menghapus data');
        }
    }

    public function detail_sidang($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa', 'konversi', 'uji_mahasiswa'])->where('id', $aktivitas)->first();
        $data_pelaksanaan_sidang = AktivitasMahasiswa::with(['revisi_sidang', 'notulensi_sidang', 'penilaian_sidang', 'revisi_sidang.dosen', 'penilaian_sidang.dosen'])->where('id', $aktivitas)->first();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        // dd($penguji);
        return view('prodi.data-akademik.sidang-mahasiswa.detail', [
            'data' => $data,
            'data_pelaksanaan' => $data_pelaksanaan_sidang,
            'penguji' => $penguji
        ]);
    }

    public function approve_hasil_sidang($aktivitas)
    {
        $data = AktivitasMahasiswa::with('anggota_aktivitas_personal')->where('id', $aktivitas)->first();
        $data_nilai_sidang = NilaiSidangMahasiswa::where('id_aktivitas', $data->id_aktivitas)->get();
        $pembimbing = BimbingMahasiswa::where('id_aktivitas', $data->id_aktivitas)->get();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->get();
        $konversi_matkul = MataKuliah::where('id_matkul', $data->mk_konversi)->first();
        $nilai_akhir = KonversiAktivitas::where('id_aktivitas', $data->id_aktivitas)->where('nim', $data->anggota_aktivitas_personal->nim)->first();

        if(count($penguji) == 0){
            return redirect()->back()->with('error', 'Dosen Penguji Belum di Setting.');
        }

        // dd(count($pembimbing)+count($penguji));
        if(count($data_nilai_sidang) != (count($pembimbing)+count($penguji))){
            return redirect()->back()->with('error', 'Nilai Sidang Belum Lengkap.');
        }

        //Generate nilai akhir sidang
        $bobot_penguji = round((60/count($penguji)),2);
        $bobot_pembimbing = round((30/count($pembimbing)),2);
        $bobot_proses_bimbingan = round((10/count($pembimbing)),2);

        $nilai_penguji = 0;
        $nilai_pembimbing = 0;
        $nilai_bimbingan = 0;

        foreach($pembimbing as $p){
            if(!$p->nilai_proses_bimbingan){
                return redirect()->back()->with('error', 'Nilai Proses Bimbingan Belum Lengkap.');
            }else{
                $nilai_bimbingan = $nilai_bimbingan + (($bobot_proses_bimbingan/100)*$p->nilai_proses_bimbingan);
            }
        }

        if(is_null($data->sk_tugas)){
            return redirect()->back()->with('error', 'SK Tugas Aktivitas Harus Di Isi.');
        }

        if(is_null($data->jadwal_ujian)){
            return redirect()->back()->with('error', 'Jadwal Ujian Tidak Boleh Kosong.');
        }else{
            foreach($data_nilai_sidang as $n){
                $n->update(['approved_prodi' => 1]);

                if($n->id_kategori_kegiatan == '110501' || $n->id_kategori_kegiatan == '110502'){
                    $nilai_penguji = $nilai_penguji + (($bobot_penguji/100)*$n->nilai_akhir_dosen);
                }else{
                    $nilai_pembimbing = $nilai_pembimbing + (($bobot_pembimbing/100)*$n->nilai_akhir_dosen);
                }
            }
        }

        // dd($nilai_penguji, $nilai_pembimbing, $nilai_bimbingan);
        $nilai_akhir_sidang = $nilai_penguji + $nilai_pembimbing + $nilai_bimbingan;

        $nilai_akhir_sidang = round($nilai_akhir_sidang,2);

        if($nilai_akhir_sidang < 0.0 || $nilai_akhir_sidang > 100.0){
            return redirect()->back()->with('error', 'Nilai di luar skala yang ditentukan.');
        }

        $skala = $this->skala_nilai($nilai_akhir_sidang);
        $nilai_indeks = $skala['nilai_indeks'];
        $nilai_huruf = $skala['nilai_huruf'];

        try {
            DB::beginTransaction();

            $data->update(['tanggal_selesai' => $data->jadwal_ujian]);

            $id_konversi_aktivitas = Uuid::uuid4()->toString();

            if(!$nilai_akhir){
                KonversiAktivitas::create(['feeder' => 0, 'id_konversi_aktivitas' => $id_konversi_aktivitas, 'id_matkul' => $konversi_matkul->id_matkul, 'nama_mata_kuliah' => $konversi_matkul->nama_mata_kuliah,'id_aktivitas' => $data->id_aktivitas, 'judul' => $data->judul, 'id_anggota' => $data->anggota_aktivitas_personal->id_anggota, 'nama_mahasiswa' => $data->anggota_aktivitas_personal->nama_mahasiswa, 'nim' => $data->anggota_aktivitas_personal->nim, 'sks_mata_kuliah' => $konversi_matkul->sks_mata_kuliah, 'nilai_angka' => $nilai_akhir_sidang, 'nilai_indeks' => $nilai_indeks, 'nilai_huruf' => $nilai_huruf, 'id_semester' => $data->id_semester, 'nama_semester' => $data->nama_semester, 'status_sync' => 'Belum Sync']);
            }else{
                if($nilai_akhir->feeder == 0){
                    $nilai_akhir->update(['nilai_angka' => $nilai_akhir_sidang, 'nilai_indeks' => $nilai_indeks, 'nilai_huruf' => $nilai_huruf]);
                }else{
                    return redirect()->back()->with('error', 'Nilai Sudah di Sinkronisasi.');
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    public function decline_sidang(Request $request, $id)
    {
        $data = $request->validate([
            'alasan_pembatalan' => 'required'
        ]);

        $data_aktivitas = AktivitasMahasiswa::with('anggota_aktivitas_personal')->where('id', $id)->first();
        $nilai_akhir = KonversiAktivitas::where('id_aktivitas', $data_aktivitas->id_aktivitas)->where('nim', $data_aktivitas->anggota_aktivitas_personal->nim)->first();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data_aktivitas->id_aktivitas)->where('status_uji_mahasiswa', 2)->where('feeder', 0)->first();

        if($nilai_akhir){
            return redirect()->back()->with('error', 'Sidang Sudah di Nilai.');
        }

        if($penguji){
            return redirect()->back()->with('error', 'Dosen Penguji Sudah Menyetujui.');
        }

        try {
            DB::beginTransaction();

            AktivitasMahasiswa::where('id',$id)->update([
                'approve_sidang' => 0,
                'alasan_pembatalan_sidang' => $request->alasan_pembatalan
            ]);

            UjiMahasiswa::where('id_aktivitas', $data_aktivitas->id_aktivitas)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan.');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    private function skala_nilai($nilai){
        switch (true) {
            case ($nilai >= 86.0 && $nilai <= 100.0):

                $nilai_indeks = '4.00';
                $nilai_huruf = 'A';

                // Return the values as an associative array
                return [
                    'nilai_indeks' => $nilai_indeks,
                    'nilai_huruf' => $nilai_huruf
                ];

            case ($nilai >= 71.0 && $nilai < 86.0):

                $nilai_indeks = '3.00';
                $nilai_huruf = 'B';

                // Return the values as an associative array
                return [
                    'nilai_indeks' => $nilai_indeks,
                    'nilai_huruf' => $nilai_huruf
                ];

            case ($nilai >= 56.0 && $nilai < 71.0):

                $nilai_indeks = '2.00';
                $nilai_huruf = 'C';

                // Return the values as an associative array
                return [
                    'nilai_indeks' => $nilai_indeks,
                    'nilai_huruf' => $nilai_huruf
                ];

            case ($nilai >= 41.0 && $nilai < 56.0):

                $nilai_indeks = '1.00';
                $nilai_huruf = 'D';

                // Return the values as an associative array
                return [
                    'nilai_indeks' => $nilai_indeks,
                    'nilai_huruf' => $nilai_huruf
                ];

            default:
                $nilai_indeks = '0.00';
                $nilai_huruf = 'E';

                // Return the values as an associative array
                return [
                    'nilai_indeks' => $nilai_indeks,
                    'nilai_huruf' => $nilai_huruf
                ];
        }
    }
}
