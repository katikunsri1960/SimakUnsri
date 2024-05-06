<?php

namespace App\Http\Controllers\Universitas;

use App\Models\SemesterAktif;
use App\Models\Referensi\PeriodePerkuliahan;
use App\Http\Controllers\Controller;
use App\Models\Dosen\BiodataDosen;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\SkalaNilai;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Feeder\FeederAPI;

class PengaturanController extends Controller
{
    public function periode_perkuliahan(Request $request)
    {
        $semester = Semester::orderBy('id_semester', 'desc')->get();
        $prodi = ProgramStudi::all();

        $data = PeriodePerkuliahan::with('prodi', 'semester')
            ->orderBy('id_semester', 'desc')
            ->when($request->id_semester, function ($query, $id_semester) {
                return $query->whereIn('id_semester', $id_semester);
            })
            ->when($request->id_prodi, function ($query, $id_prodi) {
                return $query->whereIn('id_prodi', $id_prodi);
            })
            ->get();

        return view('universitas.pengaturan.periode-perkuliahan.index', [
            'data' => $data,
            'semester' => $semester,
            'prodi' => $prodi
        ]);
    }

    public function sync_periode_perkuliahan()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $act = 'GetDetailPeriodePerkuliahan';

        $prodi = ProgramStudi::pluck('id_prodi')->toArray();

        foreach ($prodi as $p) {

            $filter = "id_prodi = '".$p."'";
            $api = new FeederApi($act, 0, 0, '', $filter);

            $response = $api->runWS();

            $data = $response['data'];

            $data = array_map(function ($value) {
                $value['tanggal_awal_perkuliahan'] = empty($value['tanggal_awal_perkuliahan']) ? null : date('Y-m-d', strtotime($value['tanggal_awal_perkuliahan']));
                $value['tanggal_akhir_perkuliahan'] = empty($value['tanggal_akhir_perkuliahan']) ? null : date('Y-m-d', strtotime($value['tanggal_akhir_perkuliahan']));
                return $value;
            }, $data);

            PeriodePerkuliahan::upsert($data, ['id_prodi', 'id_semester']);
        }

        return redirect()->back()->with('success', 'Data berhasil di sinkronisasi');

    }

    public function semester_aktif()
    {
        $semester = Semester::orderBy('id_semester', 'desc')->get();
        $data = SemesterAktif::where('id', 1)->first();
        return view('universitas.pengaturan.semester-aktif', [
            'semester' => $semester,
            'data' => $data
        ]);
    }

    public function semester_aktif_store(Request $request)
    {
        $data = $request->validate([
            'id_semester' => 'required|exists:semesters,id_semester',
            'krs_mulai' => 'required',
            'krs_selesai' => 'required',
        ]);

        $data['id'] = 1;

        SemesterAktif::updateOrCreate(['id' => 1], $data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function skala_nilai()
    {
        $data = SkalaNilai::with('prodi')->get();
        return view('universitas.pengaturan.skala-nilai.index', [
            'data' => $data
        ]);
    }

    public function sync_skala_nilai()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $act = 'GetListSkalaNilaiProdi';

        $prodi = ProgramStudi::pluck('id_prodi')->toArray();

        foreach ($prodi as $p) {

            $filter = "id_prodi = '".$p."'";
            $api = new FeederApi($act, 0, 0, '', $filter);

            $response = $api->runWS();

            $data = $response['data'];

            $data = array_map(function ($value) {
                $value['tanggal_mulai_efektif'] = empty($value['tanggal_mulai_efektif']) ? null : date('Y-m-d', strtotime($value['tanggal_mulai_efektif']));
                $value['tanggal_akhir_efektif'] = empty($value['tanggal_akhir_efektif']) ? null : date('Y-m-d', strtotime($value['tanggal_akhir_efektif']));
                return $value;
            }, $data);

            SkalaNilai::upsert($data, 'id_bobot_nilai');
        }

        return redirect()->back()->with('success', 'Data berhasil di sinkronisasi');
    }

    public function akun()
    {
        $data = User::orderBy('role')->get();
        $prodi = ProgramStudi::orderBy('kode_program_studi')->get();

        return view('universitas.pengaturan.akun.index', [
            'data' => $data,
            'prodi' => $prodi,
        ]);
    }

    public function akun_store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'role' => 'required',
            'fk_id' => 'required',
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function akun_destroy(User $user)
    {
        $user->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function get_dosen(Request $request)
    {
        $db = new BiodataDosen();

        $data = $db->where('id_jenis_sdm', 12)
                    ->where('nama_dosen', 'like', '%'.$request->q.'%')
                    ->orWhere('nidn', 'like', '%'.$request->q.'%')->get();

        return response()->json($data);

    }

    public function akun_dosen_create(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'id_dosen' => 'required',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['role'] = 'dosen';
        $data['fk_id'] = $data['id_dosen'];

        $nama = BiodataDosen::where('id_dosen', $data['id_dosen'])->pluck('nama_dosen')->first();
        $data['name'] = $nama;

        unset($data['id_dosen']);

        User::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
