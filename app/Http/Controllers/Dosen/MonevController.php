<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonevController extends Controller
{
    public function pa_prodi()
    {

        $id_dosen = auth()->user()->fk_id;
        $semester = SemesterAktif::join('semesters', 'semester_aktifs.id_semester', 'semesters.id_semester')
            ->select('semesters.id_tahun_ajaran as id_tahun_ajaran', 'semester_aktifs.id_semester as id_semester')->first();

        $id_registrasi_dosen = PenugasanDosen::where('id_dosen', $id_dosen)
            ->where('id_tahun_ajaran', $semester->id_tahun_ajaran)
            ->first()->id_registrasi_dosen;

        $id_prodi_penugasan = DosenPengajarKelasKuliah::where('id_registrasi_dosen', $id_registrasi_dosen)
            ->where('id_semester', $semester->id_semester)
            ->distinct('id_prodi')->pluck('id_prodi');

        $prodi = ProgramStudi::whereIn('id_prodi', $id_prodi_penugasan)->get();

        return view('dosen.monev.pa-prodi', [
            'prodi' => $prodi,
            'semester' => $semester,
        ]);
    }

    public function pa_prodi_get_monev(Request $request)
    {
        $id_prodi = $request->id_prodi;
        $semester = SemesterAktif::first()->id_semester;
        $db = new BimbingMahasiswa;

        $aktivitas = $db->aktivitas_pa_prodi($id_prodi, $semester);

        return response()->json([
            'status' => $aktivitas->isEmpty() ? 0 : 1,
            'message' => $aktivitas->isEmpty() ? 'Data tidak ditemukan' : 'Data berhasil diambil',
            'data' => $aktivitas->isEmpty() ? [] : $aktivitas,
        ]);
    }

    public function pa_prodi_get_anggota_monev(Request $request)
    {
        $aktivitas = AktivitasMahasiswa::where('id', $request->id)->select('id_aktivitas')->first();
        $bimbingan = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)->first();

        $data = AnggotaAktivitasMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
            ->join('riwayat_pendidikans as r', 'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', 'r.id_registrasi_mahasiswa')
            ->select('r.nim as nim', 'r.nama_mahasiswa as nama_mahasiswa', DB::raw('LEFT(id_periode_masuk, 4) as angkatan'))->get();

        return response()->json([
            'status' => $data->isEmpty() ? 0 : 1,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data berhasil diambil',
            'data' => $data->isEmpty() ? [] : $data,
            'bimbingan' => $bimbingan,
        ]);
    }

    public function karya_ilmiah()
    {
        $id_dosen = auth()->user()->fk_id;
        $semester = SemesterAktif::join('semesters', 'semester_aktifs.id_semester', 'semesters.id_semester')
            ->select('semesters.id_tahun_ajaran as id_tahun_ajaran', 'semester_aktifs.id_semester as id_semester')->first();

        $id_jenis_aktivitas = [3, 4, 22];

        $id_prodi_penugasan = BimbingMahasiswa::join('aktivitas_mahasiswas as am', 'bimbing_mahasiswas.id_aktivitas', 'am.id_aktivitas')
            ->whereIn('am.id_jenis_aktivitas', $id_jenis_aktivitas)
            ->where('bimbing_mahasiswas.id_dosen', $id_dosen)
            ->where('am.id_semester', $semester->id_semester)
            ->select('am.id_prodi as id_prodi')
            ->distinct('am.id_prodi')
            ->pluck('id_prodi');

        // dd($id_prodi_penugasan);

        $prodi = ProgramStudi::whereIn('id_prodi', $id_prodi_penugasan)
            ->select('id_prodi', 'nama_jenjang_pendidikan', 'nama_program_studi')->get();

        return view('dosen.monev.karya-ilmiah.index', [
            'prodi' => $prodi,
            'semester' => $semester,
        ]);
    }

    public function karya_ilmiah_get_data(Request $request)
    {
        $id_prodi = $request->id_prodi;
        $semester = SemesterAktif::first()->id_semester;
        $db = new BimbingMahasiswa;
        $id_jenis_aktivitas = [3, 4, 22];
        $rawJenis_aktivitas = '3,4,22';
        $kategori_pembimbing_utama = '110401,110402,110403,110404';
        $kategori_pembimbing_pendamping = '110405,110406,110407,110408';

        $dosen = $db->join('aktivitas_mahasiswas as am', 'bimbing_mahasiswas.id_aktivitas', 'am.id_aktivitas')
            ->whereIn('am.id_jenis_aktivitas', $id_jenis_aktivitas)
            ->where('am.id_semester', $semester)
            ->where('am.id_prodi', $id_prodi)
            ->select('bimbing_mahasiswas.id_dosen')
            ->distinct('bimbing_mahasiswas.id_dosen')
            ->pluck('bimbing_mahasiswas.id_dosen');

        $rawPembimbingUtama = '(SELECT COUNT(*) from bimbing_mahasiswas as bm
                                JOIN aktivitas_mahasiswas as am on bm.id_aktivitas = am.id_aktivitas
                                WHERE id_dosen = biodata_dosens.id_dosen AND am.id_semester = '.$semester.'
                                AND am.id_jenis_aktivitas IN ('.$rawJenis_aktivitas.')
                                AND bm.id_kategori_kegiatan IN ('.$kategori_pembimbing_utama.')) as pembimbing_utama';

        $rawPembimbingPendamping = '(SELECT COUNT(*) from bimbing_mahasiswas as bm
                                JOIN aktivitas_mahasiswas as am on bm.id_aktivitas = am.id_aktivitas
                                WHERE id_dosen = biodata_dosens.id_dosen AND am.id_semester = '.$semester.'
                                AND am.id_jenis_aktivitas IN ('.$rawJenis_aktivitas.')
                                AND bm.id_kategori_kegiatan IN ('.$kategori_pembimbing_pendamping.')) as pembimbing_pendamping';

        $data = BiodataDosen::whereIn('id_dosen', $dosen)
            ->select('nidn', 'nama_dosen', 'id_dosen',
                DB::raw($rawPembimbingUtama), DB::raw($rawPembimbingPendamping))->get();

        return response()->json([
            'status' => $data->isEmpty() ? 0 : 1,
            'message' => $data->isEmpty() ? 'Data tidak ditemukan' : 'Data berhasil diambil',
            'data' => $data->isEmpty() ? [] : $data,
        ]);
    }

    public function karya_ilmiah_pembimbing_utama($dosen)
    {
        $semester = SemesterAktif::first()->id_semester;
        $id_jenis_aktivitas = [3, 4, 22];
        // $rawJenis_aktivitas = '1,2,3,4,22';
        $kategori_pembimbing_utama = [110401, 110402, 110403, 110404];

        $aktivitas = BimbingMahasiswa::join('aktivitas_mahasiswas as am', 'bimbing_mahasiswas.id_aktivitas', 'am.id_aktivitas')
            ->where('bimbing_mahasiswas.id_dosen', $dosen)
                                // ->whereIn('am.id_jenis_aktivitas', $id_jenis_aktivitas)
            ->where('am.id_semester', $semester)
            ->whereIn('bimbing_mahasiswas.id_kategori_kegiatan', $kategori_pembimbing_utama)
            ->whereIn('am.id_jenis_aktivitas', $id_jenis_aktivitas)
            ->select('am.id_aktivitas')
            ->distinct('am.id_aktivitas')
            ->pluck('am.id_aktivitas');

        $data = AnggotaAktivitasMahasiswa::join('riwayat_pendidikans as rp', 'rp.id_registrasi_mahasiswa', 'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa')
            ->join('aktivitas_mahasiswas as am', 'anggota_aktivitas_mahasiswas.id_aktivitas', 'am.id_aktivitas')
            ->join('program_studis as prodi', 'prodi.id_prodi', 'rp.id_prodi')
            ->leftJoin('mata_kuliahs as mk', 'am.mk_konversi', 'mk.id_matkul')
            ->whereIn('anggota_aktivitas_mahasiswas.id_aktivitas', $aktivitas)
            ->select('rp.nim', 'rp.nama_mahasiswa', DB::raw('LEFT(rp.id_periode_masuk, 4) as angkatan'), 'prodi.nama_program_studi', 'prodi.nama_jenjang_pendidikan',
                'am.judul', 'mk.nama_mata_kuliah', 'am.nama_jenis_aktivitas')
            ->orderBy('rp.id_prodi')
            ->get();

        $data_dosen = BiodataDosen::where('id_dosen', $dosen)->select('nama_dosen')->first();

        return view('dosen.monev.karya-ilmiah.pembimbing-utama', [
            'data' => $data,
            'dosen' => $data_dosen,
        ]);
    }

    public function karya_ilmiah_pembimbing_pendamping($dosen)
    {
        $semester = SemesterAktif::first()->id_semester;
        $id_jenis_aktivitas = [3, 4, 22];
        $kategori_pembimbing_pendamping = [110405, 110406, 110407, 110408];

        $aktivitas = BimbingMahasiswa::join('aktivitas_mahasiswas as am', 'bimbing_mahasiswas.id_aktivitas', 'am.id_aktivitas')
            ->where('bimbing_mahasiswas.id_dosen', $dosen)
            ->whereIn('am.id_jenis_aktivitas', $id_jenis_aktivitas)
            ->where('am.id_semester', $semester)
            ->whereIn('bimbing_mahasiswas.id_kategori_kegiatan', $kategori_pembimbing_pendamping)
            ->select('am.id_aktivitas')
            ->distinct('am.id_aktivitas')
            ->pluck('am.id_aktivitas');

        $data = AnggotaAktivitasMahasiswa::join('riwayat_pendidikans as rp', 'rp.id_registrasi_mahasiswa', 'anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa')
            ->join('aktivitas_mahasiswas as am', 'anggota_aktivitas_mahasiswas.id_aktivitas', 'am.id_aktivitas')
            ->join('program_studis as prodi', 'prodi.id_prodi', 'rp.id_prodi')
            ->leftJoin('mata_kuliahs as mk', 'am.mk_konversi', 'mk.id_matkul')
            ->whereIn('anggota_aktivitas_mahasiswas.id_aktivitas', $aktivitas)
            ->select('rp.nim', 'rp.nama_mahasiswa', DB::raw('LEFT(rp.id_periode_masuk, 4) as angkatan'), 'prodi.nama_program_studi', 'prodi.nama_jenjang_pendidikan',
                'am.judul', 'mk.nama_mata_kuliah', 'am.nama_jenis_aktivitas')
            ->orderBy('rp.id_prodi')
            ->get();

        $data_dosen = BiodataDosen::where('id_dosen', $dosen)->select('nama_dosen')->first();

        return view('dosen.monev.karya-ilmiah.pembimbing-pendamping', [
            'data' => $data,
            'dosen' => $data_dosen,
        ]);
    }

    public function penguji_sidang() {}
}
