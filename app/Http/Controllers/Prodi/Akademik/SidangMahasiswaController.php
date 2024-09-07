<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\UjiMahasiswa;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SidangMahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $semesterAktif = SemesterAktif::first();
        $semester = $semesterAktif->id_semester;
        $db = new AktivitasMahasiswa();
        $data = $db->sidang(auth()->user()->fk_id, $semester );
        // dd($data);
        return view('prodi.data-akademik.sidang-mahasiswa.index', [
            'data' => $data,
            'semester' => $semester,
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
        $semesterAktif = SemesterAktif::first();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'uji_mahasiswa'])->where('id_aktivitas', $aktivitas)->where('id_semester', $semesterAktif->id_semester)->first();
        // dd($data);

        return view('prodi.data-akademik.sidang-mahasiswa.edit', [
            'd' => $data
        ]);
    }

    public function update_detail_sidang($aktivitas, Request $request)
    {
        $detik = "00";
        $tahun_aktif = date('Y');

        $data = $request->validate([
                    'tanggal_ujian' => 'required',
                    'bulan_ujian' => 'required',
                    'tahun_ujian' => 'required',
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
        $semesterAktif = SemesterAktif::first();
        $kategori = KategoriKegiatan::whereIn('id_kategori_kegiatan', ['110501', '110502'])->get();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'uji_mahasiswa'])->where('id_aktivitas', $aktivitas)->where('id_semester', $semesterAktif->id_semester)->first();
        // dd($data);

        return view('prodi.data-akademik.sidang-mahasiswa.tambah-dosen', [
            'd' => $data,
            'kategori' => $kategori
        ]);
    }

    public function store_dosen_penguji($aktivitas, Request $request)
    {
        $semester_aktif = SemesterAktif::first();
        $data = $request->validate([
                    'dosen_penguji.*' => 'required',
                    'kategori.*' => 'required',
                    'penguji_ke.*' => 'required',
                ]);
        try {
            DB::beginTransaction();

            $aktivitas_mahasiswa = AktivitasMahasiswa::where('id_aktivitas', $aktivitas)->first();

            $dosen_penguji = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->whereIn('id_registrasi_dosen', $request->dosen_penguji)->get();

            if($dosen_penguji->count() == 0 || $dosen_penguji->count() != count($request->dosen_penguji)){
                $dosen_penguji = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->whereIn('id_registrasi_dosen', $request->dosen_penguji)->get();
            }
            //Count jumlah dosen penguji
            $jumlah_dosen=count($request->dosen_penguji);

            for($i=0;$i<$jumlah_dosen;$i++){
                //Generate id uji mahasiswa
                $id_uji_mahasiswa = Uuid::uuid4()->toString();
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->where('id_registrasi_dosen', $request->dosen_penguji[$i])->first();
                if(!$dosen)
                {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->where('id_registrasi_dosen', $request->dosen_penguji[$i])->first();
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
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
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
        $data = $request->validate([
                    'dosen_penguji' => 'required',
                    'kategori.*' => 'required',
                    'penguji_ke.*' => 'required',
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

                $kategori = KategoriKegiatan::where('id_kategori_kegiatan', $request->kategori[$i])->first();
                // dd($kategori);

                //Store data to table tanpa substansi kuliah
                UjiMahasiswa::where('id', $uji)->update(['id_kategori_kegiatan' => $kategori->id_kategori_kegiatan, 'nama_kategori_kegiatan' => $kategori->nama_kategori_kegiatan, 'id_dosen'=> $dosen->id_dosen, 'nidn' => $dosen->nidn, 'nama_dosen' => $dosen->nama_dosen, 'penguji_ke' => $request->penguji_ke[$i], 'status_uji_mahasiswa' => 0]);

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.sidang-mahasiswa.edit-detail', $aktivitas)->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
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
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
