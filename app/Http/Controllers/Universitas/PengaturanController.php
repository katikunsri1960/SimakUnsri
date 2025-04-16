<?php

namespace App\Http\Controllers\Universitas;

use App\Http\Controllers\Controller;
use App\Imports\PeriodeImport;
use App\Models\Dosen\BiodataDosen;
use App\Models\Fakultas;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\ProgramStudi;
use App\Models\Referensi\PeriodePerkuliahan;
use App\Models\Semester;
use App\Models\SemesterAktif;
use App\Models\SkalaNilai;
use App\Models\User;
use App\Services\Feeder\FeederAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            'prodi' => $prodi,
        ]);
    }

    public function periode_perkuliahan_upload(Request $request)
    {
        $data = $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');
        $import = Excel::import(new PeriodeImport, $file);

        return redirect()->back()->with('success', 'Data successfully imported!');
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
            'data' => $data,
        ]);
    }

    public function semester_aktif_store(Request $request)
    {
        $data = $request->validate([
            'id_semester' => 'required|exists:semesters,id_semester',
            'semester_allow' => 'required',
            'krs_mulai' => 'required',
            'krs_selesai' => 'required',
            'mulai_isi_nilai' => 'required',
            'batas_isi_nilai' => 'required',
            'tanggal_mulai_kprs' => 'required',
            'tanggal_akhir_kprs' => 'required',
            'batas_bayar_ukt' => 'required',
            'tgl_mulai_pengajuan_cuti' => 'required',
            'tgl_selesai_pengajuan_cuti' => 'required',
        ]);
        // dd($data);
        $data['id'] = 1;

        SemesterAktif::updateOrCreate(['id' => 1], $data);

        // dd($data);
        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function skala_nilai()
    {
        $data = SkalaNilai::with('prodi')->get();

        return view('universitas.pengaturan.skala-nilai.index', [
            'data' => $data,
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

    public function akun(Request $request)
    {
        // $data = User::orderBy('role')->limit(100)->get();
        $prodi = ProgramStudi::orderBy('kode_program_studi')->get();

        return view('universitas.pengaturan.akun.index', [
            // 'data' => $data,
            'prodi' => $prodi,
        ]);
    }

    public function akun_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = User::query();

        if ($searchValue) {
            $query = $query->where('username', 'like', '%'.$searchValue.'%')
                ->orWhere('name', 'like', '%'.$searchValue.'%');
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['role', 'username', 'name'];

            // if ($columns[$orderColumn] == 'prodi') {
            //     $query = $query->join('program_studis as prodi', 'mata_kuliahs.id_prodi', '=', 'prodi.id')
            //         ->orderBy('prodi.nama_jenjang_pendidikan', $orderDirection)
            //         ->orderBy('prodi.nama_program_studi', $orderDirection)
            //         ->select('mata_kuliahs.*', 'prodi.nama_jenjang_pendidikan', 'prodi.nama_program_studi'); // Avoid column name conflicts
            // } else {
            $query = $query->orderBy($columns[$orderColumn], $orderDirection);
            // }
        }

        $data = $query->skip($offset)->take($limit)->get();

        $recordsTotal = User::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function akun_store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'name' => 'required',
            'email' => 'nullable|email',
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
        $db = new BiodataDosen;

        $data = $db->where('id_jenis_sdm', 12)
            ->where('nama_dosen', 'like', '%'.$request->q.'%')
            ->orWhere('nidn', 'like', '%'.$request->q.'%')->get();

        return response()->json($data);

    }

    public function get_mahasiswa(Request $request)
    {
        $db = new RiwayatPendidikan;

        $data = $db->where('nim', 'like', '%'.$request->q.'%')
            ->orWhere('nama_mahasiswa', 'like', '%'.$request->q.'%')
            ->orderBy('id_periode_masuk', 'desc')->get();

        return response()->json($data);
    }

    public function get_fakultas(Request $request)
    {
        $db = new Fakultas;

        $data = $db->where('nama_fakultas', 'like', '%'.$request->q.'%')->get();

        return response()->json($data);
    }

    public function akun_fakultas_create(Request $request)
    {
        $data = $request->validate([
            'role' => 'required',
            'username' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'fk_id' => 'required|exists:fakultas,id',
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['role'] = 'fakultas';
        try {
            DB::beginTransaction();

            User::create($data);

            DB::commit();
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();

            return redirect()->back()->with('error', 'Data gagal disimpan. ');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan');
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

    public function akun_mahasiswa_create(Request $request)
    {
        $data = $request->validate([
            'fk_id' => 'required',
            'password' => 'required|confirmed',
        ]);

        $db = new RiwayatPendidikan;
        $mhs = $db->where('id_registrasi_mahasiswa', $data['fk_id'])->first();

        $data['password'] = bcrypt($data['password']);
        $data['name'] = $mhs->nama_mahasiswa;
        $data['username'] = $mhs->nim;
        $data['email'] = $mhs->nim.'@student.unsri.ac.id';
        $data['role'] = 'mahasiswa';

        try {
            DB::beginTransaction();
            User::create($data);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            return redirect()->back()->with('error', 'Data gagal disimpan. ');
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function akun_lain_create(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|unique:users,username',
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'role' => 'required',
        ]);

        $data['password'] = bcrypt($data['password']);

        User::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
