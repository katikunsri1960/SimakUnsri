<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\BatasIsiKRSManual;
use App\Models\Mahasiswa\LulusDo;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;

class KRSManualController extends Controller
{
    public function index(Request $request)
    {
        $data = BatasIsiKRSManual::with(['riwayat'])->get();
        // $semester = Semester::orderBy('id_semester', 'desc')->get();

        return view('universitas.batas-isi-krs-manual.index', compact('data'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'status_bayar' => 'required | in:0,1,2,3',
            'batas_isi_krs'=>'required | date'
        ]);

        $riwayat_pendidikan= RiwayatPendidikan::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();
        // $check = LulusDo::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        // if ($check) {
        //     return redirect()->back()->with('error', 'Mahasiswa sudah ada pada data Lulus Do Feeder!!');
        // }
        // dd($request->tanggal_pembayaran);
        // $data['id_semester'] = SemesterAktif::first()->id_semester;
        $data['nim'] = $riwayat_pendidikan->nim;
        $data['nama_mahasiswa'] = $riwayat_pendidikan->nama_mahasiswa;
        $data['keterangan'] = $request->keterangan;

        BatasIsiKRSManual::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function update(BatasIsiKRSManual $idmanual, Request $request)
    {
        $data = $request->validate([
            'batas_isi_krs'=>'required | date'
        ]);

        $idmanual->update($data);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(BatasIsiKRSManual $idmanual)
    {
        $idmanual->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function pembatalan_krs()
    {
        return view('universitas.pembatalan-krs.index');
    }

    public function pembatalan_krs_data(Request $request)
    {
        $semester = SemesterAktif::first()->id_semester;
        $nim = $request->nim;

        $riwayat = RiwayatPendidikan::with('dosen_pa', 'prodi.jurusan', 'prodi.fakultas')->where('nim', $nim)->orderBy('id_periode_masuk', 'desc')->first();
        // dd($riwayat);
        if (!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan',
            ]);
        }

        $krs = PesertaKelasKuliah::with('kelas_kuliah.matkul')->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa)
                ->whereHas('kelas_kuliah' , function($query) use ($semester) {
                    $query->where('id_semester', $semester);
                })
                ->get();

        $aktivitas = AktivitasMahasiswa::with('anggota_aktivitas_personal', 'konversi')
                ->whereHas('anggota_aktivitas_personal', function($query) use ($riwayat) {
                    $query->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa);
                })
                ->where('id_semester', $semester)
                ->whereIn('id_jenis_aktivitas', [1,2,3,4,5,6,22])
                ->get();

        $aktivitas_mbkm = AktivitasMahasiswa::with('anggota_aktivitas_personal', 'konversi')
                    ->whereHas('anggota_aktivitas_personal', function($query) use ($riwayat) {
                        $query->where('id_registrasi_mahasiswa', $riwayat->id_registrasi_mahasiswa);
                    })
                    ->where('id_semester', $semester)
                    ->whereIn('id_jenis_aktivitas',[13,14,15,16,17,18,19,20,21])
                    ->get();

        if($krs->isEmpty() && $aktivitas->isEmpty() && $aktivitas_mbkm->isEmpty()) {
            $response = [
                'status' => 'error',
                'message' => 'Data KRS tidak ditemukan!',
            ];
            return response()->json($response);
        }

        $response = [
            'status' => 'success',
            'message' => 'Data KRS berhasil diambil',
            'krs' => $krs,
            'aktivitas' => $aktivitas,
            'aktivitas_mbkm' => $aktivitas_mbkm,
            'riwayat' => $riwayat,
        ];


        return response()->json($response);
    }

    public function pembatalan_krs_store(Request $request)
    {
        $nim = $request->nim;

        $riwayat = RiwayatPendidikan::where('nim', $nim)->orderBy('id_periode_masuk', 'desc')->first();

        if(!$riwayat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data mahasiswa tidak ditemukan',
            ]);
        }

        $db = new PesertaKelasKuliah();

        $response = $db->batal_all($riwayat->id_registrasi_mahasiswa);

        return response()->json($response);
    }
}
