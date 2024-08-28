<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class AktivitasNonTAController extends Controller
{
    public function index(Request $request)
    {
        $semesterAktif = SemesterAktif::first();
        $semester = $semesterAktif->id_semester;
        $db = new AktivitasMahasiswa();
        $data = $db->aktivitas_non_ta(auth()->user()->fk_id, $semester );
        // dd($data);
        return view('prodi.data-akademik.non-tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester,
        ]);
    } 

    public function approve_pembimbing(AktivitasMahasiswa $aktivitasMahasiswa)
    {
        $db = new AktivitasMahasiswa();
        $result = $db->approve_pembimbing($aktivitasMahasiswa->id_aktivitas);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function ubah_detail_non_tugas_akhir($aktivitas)
    {
        $semesterAktif = SemesterAktif::first();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa'])->where('id_aktivitas', $aktivitas)->where('id_semester', $semesterAktif->id_semester)->first();
        // dd($data);

        return view('prodi.data-akademik.non-tugas-akhir.edit', [
            'd' => $data
        ]);
    }

    public function update_detail_non_tugas_akhir($aktivitas, Request $request)
    {
        $tahun_aktif = date('Y');

        $data = $request->validate([
                    'sk_tugas' => 'required',
                    'tanggal_sk' => 'required',
                    'bulan_sk' => 'required',
                ]);
        //Generate tanggal sk tugas
        $tanggal_sk_tugas = $tahun_aktif."-".$request->bulan_sk."-".$request->tanggal_sk;

        AktivitasMahasiswa::where('id_aktivitas', $aktivitas)->update(['sk_tugas' => $request->sk_tugas, 'tanggal_sk_tugas' => $tanggal_sk_tugas]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');
        // $prodi_id = auth()->user()->fk_id;
        $tahun_ajaran = SemesterAktif::with('semester')->first();
        // $tahun_ajaran = Semester::where('id_semester','=','20231')->where('a_periode_aktif','=','1')->get();

        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1)
                                ->orderby('nama_dosen', 'asc');
        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%")
                  ->where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1);
        }

        $data = $query->get();

        return response()->json($data);
    }

    public function tambah_dosen_pembimbing($aktivitas)
    {
        $semesterAktif = SemesterAktif::first();
        $kategori = KategoriKegiatan::whereIn('id_kategori_kegiatan', ['110300'])->get();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa'])->where('id_aktivitas', $aktivitas)->where('id_semester', $semesterAktif->id_semester)->first();
        // dd($data);

        return view('prodi.data-akademik.non-tugas-akhir.tambah-dosen', [
            'd' => $data,
            'kategori' => $kategori
        ]);
    }

    public function store_dosen_pembimbing($aktivitas, Request $request)
    {
        $semester_aktif = SemesterAktif::first();
        $data = $request->validate([
                    'dosen_pembimbing.*' => 'required',
                    'kategori.*' => 'required',
                    'pembimbing_ke.*' => 'required',
                ]);
        try {
            DB::beginTransaction();

            $aktivitas_mahasiswa = AktivitasMahasiswa::where('id_aktivitas', $aktivitas)->first();

            $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->whereIn('id_registrasi_dosen', $request->dosen_pembimbing)->get();

            if($dosen_pembimbing->count() == 0 || $dosen_pembimbing->count() != count($request->dosen_pembimbing)){
                $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->whereIn('id_registrasi_dosen', $request->dosen_pembimbing)->get();
            }
            //Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen=count($request->dosen_pembimbing);

            for($i=0;$i<$jumlah_dosen;$i++){
                //Generate id aktivitas mengajar
                $id_bimbing_mahasiswa = Uuid::uuid4()->toString();
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->where('id_registrasi_dosen', $request->dosen_pembimbing[$i])->first();
                if(!$dosen)
                {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->where('id_registrasi_dosen', $request->dosen_pembimbing[$i])->first();
                }

                $kategori = KategoriKegiatan::where('id_kategori_kegiatan', $request->kategori[$i])->first();
                // dd($kategori);

                //Store data to table tanpa substansi kuliah
                BimbingMahasiswa::create(['feeder'=> 0, 'approved' => 0, 'approved_dosen' => 0,'id_bimbing_mahasiswa'=> $id_bimbing_mahasiswa, 'id_aktivitas'=> $aktivitas, 'judul' => $aktivitas_mahasiswa->judul, 'id_kategori_kegiatan' => $kategori->id_kategori_kegiatan, 'nama_kategori_kegiatan' => $kategori->nama_kategori_kegiatan, 'id_dosen'=> $dosen->id_dosen, 'nidn' => $dosen->nidn, 'nama_dosen' => $dosen->nama_dosen, 'pembimbing_ke' => $request->pembimbing_ke[$i], 'status_sync' => 'belum sync']);

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.tugas-akhir.edit-detail', $aktivitas)->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
        }
    }

    public function edit_dosen_pembimbing($bimbing)
    {
        $semesterAktif = SemesterAktif::first();
        $kategori = KategoriKegiatan::whereIn('id_kategori_kegiatan', ['110405', '110403', '110406', '110402', '110401', '110407'])->get();
        $data = BimbingMahasiswa::with(['anggota_aktivitas_personal'])->where('id', $bimbing)->first();
        // dd($data);

        return view('prodi.data-akademik.tugas-akhir.edit-dosen', [
            'data' => $data,
            'kategori' => $kategori
        ]);
    }

    public function update_dosen_pembimbing($bimbing, $aktivitas, Request $request)
    {
        // dd($aktivitas);
        $semester_aktif = SemesterAktif::first();
        $data = $request->validate([
                    'dosen_pembimbing' => 'required',
                    'kategori.*' => 'required',
                    'pembimbing_ke.*' => 'required',
                ]);
        try {
            DB::beginTransaction();

            $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->whereIn('id_dosen', $request->dosen_pembimbing)->get();

            if($dosen_pembimbing->count() == 0 || $dosen_pembimbing->count() != count($request->dosen_pembimbing)){
                $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->whereIn('id_dosen', $request->dosen_pembimbing)->get();
            }
            //Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen=count($request->dosen_pembimbing);

            for($i=0;$i<$jumlah_dosen;$i++){
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->where('id_dosen', $request->dosen_pembimbing[$i])->first();
                if(!$dosen)
                {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->where('id_dosen', $request->dosen_pembimbing[$i])->first();
                }

                $kategori = KategoriKegiatan::where('id_kategori_kegiatan', $request->kategori[$i])->first();
                // dd($kategori);

                //Store data to table tanpa substansi kuliah
                BimbingMahasiswa::where('id', $bimbing)->update(['id_kategori_kegiatan' => $kategori->id_kategori_kegiatan, 'nama_kategori_kegiatan' => $kategori->nama_kategori_kegiatan, 'id_dosen'=> $dosen->id_dosen, 'nidn' => $dosen->nidn, 'nama_dosen' => $dosen->nama_dosen, 'pembimbing_ke' => $request->pembimbing_ke[$i]]);

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.tugas-akhir.edit-detail', $aktivitas)->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
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
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
