<?php

namespace App\Http\Controllers\Universitas;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\BatasIsiKRSManual;
use App\Models\Mahasiswa\LulusDo;
use App\Http\Controllers\Controller;
use App\Imports\BatasIsiKRSImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PembayaranManualImport;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;

class KRSManualController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'semester_view' => 'nullable|exists:semesters,id_semester'
        ]);

        $semester = Semester::select('id_semester', 'nama_semester')->orderBy('id_semester', 'desc')->get();

        $semester_view = $request->semester_view ?? null;
        $semester_pilih = $semester_view == null ? SemesterAktif::first()->id_semester : $semester_view;

        $data = BatasIsiKRSManual::with(['riwayat'])->where('id_semester', $semester_pilih)->get();

        return view('universitas.batas-isi-krs-manual.index', [
            'data' => $data,
            'semester' => $semester,
            'semester_pilih' => $semester_pilih,
        ]);
    }

    public function getDataById($id)
    {
        $data = BatasIsiKRSManual::find($id);
        // dd($data);
        if (!$data) {
            return response()->json(['error' => 'Data not found'], 404);
        }

        return response()->json($data);
    }

    public function edit($id)
    {
        // dd($id);
        $batasIsiKrs = BatasIsiKrsManual::findOrFail($id);  // Ambil data berdasarkan ID
        $mahasiswaList = BatasIsiKrsManual::all();  // Ambil semua data mahasiswa (atau bisa menggunakan query khusus)

        return view('univ.batas_isi_krs_manual.edit', compact('batasIsiKrs', 'mahasiswaList'));
    }

    public function store(Request $request)
    {
        // dd($request->id);
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'status_bayar' => 'required | in:0,1,2,3',
            'batas_isi_krs'=>'required | date'
        ]);

        $semester_aktif= SemesterAktif::first();
        $batas_isi_krs_manual=BatasIsiKRSManual::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])
                                ->where('id_semester', $semester_aktif->id_semester)->first();

        $riwayat_pendidikan= RiwayatPendidikan::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();
        // $check = LulusDo::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        if ($batas_isi_krs_manual) {
            return redirect()->back()->with('error', 'Mahasiswa sudah ada pada Batas Isi KRS Manual!');
        }
        // dd($request->tanggal_pembayaran);
        // $data['id_semester'] = SemesterAktif::first()->id_semester;
        $data['nim'] = $riwayat_pendidikan->nim;
        $data['nama_mahasiswa'] = $riwayat_pendidikan->nama_mahasiswa;
        $data['id_semester'] = $semester_aktif->id_semester;
        $data['keterangan'] = $request->keterangan;


        BatasIsiKRSManual::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // Validasi data yang dikirim dari form
        $data = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'status_bayar' => 'required|in:0,1,2,3',
            'batas_isi_krs' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        // Ambil data Semester Aktif
        $semester_aktif = SemesterAktif::first();

        // Ambil data berdasarkan ID
        $batasIsiKRSManual = BatasIsiKRSManual::findOrFail($id);

        // Ambil data riwayat pendidikan
        $riwayat_pendidikan = RiwayatPendidikan::where('id_registrasi_mahasiswa', $data['id_registrasi_mahasiswa'])->first();

        // Update data
        $batasIsiKRSManual->update([
            'id_registrasi_mahasiswa' => $data['id_registrasi_mahasiswa'],
            'nim' => $riwayat_pendidikan->nim,
            'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
            'status_bayar' => $data['status_bayar'],
            'batas_isi_krs' => $data['batas_isi_krs'],
            'id_semester' => $semester_aktif->id_semester,
            'keterangan' => $data['keterangan'],
        ]);

        // Redirect setelah sukses
        // return redirect()->route('univ.batas-isi-krs-manual.index')->with('success', 'Data berhasil diperbarui.');
        return redirect()->back()->with('success', 'Berhasil mengubah data barang!');
    }


    public function destroy(BatasIsiKRSManual $idmanual)
    {
        $idmanual->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function upload(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('file');
        $import = Excel::import(new BatasIsiKRSImport(), $file);

        return redirect()->back()->with('success', "Data successfully imported!");
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

    public function pembatalan_krs_approve(Request $request)
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

        $response = $db->approve_all_univ($riwayat->id_registrasi_mahasiswa);

        return response()->json($response);
    }
}
