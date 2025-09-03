<?php

namespace App\Http\Controllers\Fakultas\Master;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\PejabatFakultas;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Dosen\BiodataDosen;
use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa\RiwayatPendidikan;

class PejabatFakultasController extends Controller
{
    //PEJABAT KULIAH
    public function pejabat_fakultas(Request $request)
    {
        return view('fakultas.data-master.pejabat-fakultas.devop');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $fakultas_id = auth()->user()->fk_id;

        $data = PejabatFakultas::with(['dosen', 'prodi'])->where('id_fakultas', $fakultas_id)->orderBy('id_jabatan', 'ASC')->get();
        // dd($data);

        return view('fakultas.data-master.pejabat-fakultas.index', compact('data'));
    }

    public function store(Request $request)
    {
        $fakultas_id = auth()->user()->fk_id;
        // dd($request->all());

        $data = $request->validate([
            'id_dosen' => 'required|string|max:255',
            'id_jabatan' => 'required|in:0,1,2,3',
            'tgl_mulai_jabatan' => 'required|date',
            'tgl_selesai_jabatan' => 'required|date',
            'gelar_depan' => 'nullable|string',
            'gelar_belakang' => 'nullable|string' ,
        ]);

        $semester_aktif = SemesterAktif::first();

        $tahun_ajaran = substr($semester_aktif->id_semester, 0, 4);

        try {
            $dosen = PenugasanDosen::with(['prodi', 'prodi.fakultas', 'biodata' ])->where('id_tahun_ajaran', $tahun_ajaran-1)
                    ->where('id_dosen', $request->id_dosen)
                    ->firstOrFail();

            $fakultas= Fakultas::where('id', $fakultas_id)->first();

            if ($request->id_jabatan == 0) {
                $nama_jabatan = 'Dekan Fakultas';
            } elseif ($request->id_jabatan == 1) {
                $nama_jabatan = 'Wakil Dekan Bidang Akademik, Kemahasiswaan, dan Penjaminan Mutu';
            } elseif ($request->id_jabatan == 2) {
                $nama_jabatan = 'Wakil Dekan Bidang Perencanaan, Keuangan, Sumber Daya, Pengadaan, dan Logistik';
            } elseif ($request->id_jabatan == 3) {
                $nama_jabatan = 'Wakil Dekan Bidang Penelitian, Pengabdian Kepada Masyarakat, Inovasi, Hilirisasi, Kerjasama, Internasionalisasi, dan Alumni';
            } else{
                $nama_jabatan = 'Tidak Diisi';
            }

            // dd($dosen);

            $data['id_registrasi_dosen'] = $dosen->id_registrasi_dosen;
            $data['id_dosen'] = $dosen->id_dosen;
            $data['id_jabatan'] = $request->id_jabatan;
            $data['nama_jabatan'] = $nama_jabatan;
            $data['nidn'] = $dosen->nidn;
            $data['nama_dosen'] = $dosen->nama_dosen;
            $data['nip'] = $dosen->biodata->nip;
            $data['gelar_depan'] = $request->gelar_depan;
            $data['gelar_belakang'] = $request->gelar_belakang;
            $data['id_fakultas'] = $fakultas->id;
            $data['nama_fakultas'] = $fakultas->nama_fakultas;
            $data['tgl_mulai_jabatan'] = $request->tgl_mulai_jabatan;
            $data['tgl_selesai_jabatan'] = $request->tgl_selesai_jabatan;

            PejabatFakultas::create($data);

            // Melakukan debug dengan dd() untuk menampilkan data yang berhasil disimpan
            // dd($data);

            return redirect()->back()->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi masalah saat menyimpan data.' );
        }
    }


    public function get_dosen(Request $request)
    {
        $search = $request->get('q');


        $tahun_ajaran = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();

        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran-1)
                                ->orderby('nama_dosen', 'asc');
        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%")
                  ->where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran);
        }

        $data = $query->get();
        // dd($data);

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'id_dosen' => 'required',
            'id_jabatan' => 'required',
            'tgl_mulai_jabatan' => 'required|date',
            'tgl_selesai_jabatan' => 'required|date|after_or_equal:tgl_mulai_jabatan',
            'gelar_depan' => 'nullable|string|max:255',
            'gelar_belakang' => 'nullable|string|max:255',
        ]);

        try {
            // Cari data pejabat fakultas berdasarkan ID
            $data = PejabatFakultas::findOrFail($id);

            // Update data pejabat
            $data->id_dosen = $request->id_dosen;
            $data->id_jabatan = $request->id_jabatan;
            $data->tgl_mulai_jabatan = $request->tgl_mulai_jabatan;
            $data->tgl_selesai_jabatan = $request->tgl_selesai_jabatan;
            $data->gelar_depan = $request->gelar_depan;
            $data->gelar_belakang = $request->gelar_belakang;

            // Simpan perubahan
            $data->save();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('pejabat-fakultas.index')->with('success', 'Data pejabat berhasil diperbarui.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi
            return redirect()->back()->with('error', 'Terjadi masalah saat menyimpan data. Silakan coba lagi.');
        }
    }


    public function destroy(PejabatFakultas $id_pejabat)
    {
        $id_pejabat->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
