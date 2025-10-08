<?php

namespace App\Http\Controllers\Bak;

use Carbon\Carbon;
use App\Models\Fakultas;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\PenundaanBayar;
use App\Models\MonitoringIsiKrs;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Connection\Tagihan;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Monitoring\MonevStatusMahasiswa;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Monitoring\MonevStatusMahasiswaDetail;

class MonitoringController extends Controller
{
    public function pengisian_krs()
    {
        $semesterAktif = SemesterAktif::first()->id_semester;
        $data = MonitoringIsiKrs::with(['prodi'])->join('program_studis', 'monitoring_isi_krs.id_prodi', 'program_studis.id_prodi')
                ->join('fakultas', 'fakultas.id', 'program_studis.fakultas_id')
                ->orderBy('program_studis.fakultas_id')
                ->orderBy('program_studis.kode_program_studi')
                ->where('monitoring_isi_krs.id_semester', $semesterAktif)
                ->get();

        return view('bak.monitoring.pengisian-krs.index', [
            'data' => $data,
        ]);
    }

    public function detail_mahasiswa_aktif(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $data = RiwayatPendidikan::whereDoesntHave('lulus_do')
                ->where('id_prodi', $id_prodi)
                // ->whereNull('id_jenis_keluar')
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('bak.monitoring.pengisian-krs.detail-mahasiswa-aktif', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_aktif_min_tujuh(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $data = RiwayatPendidikan::whereDoesntHave('lulus_do')
                ->where('id_prodi', $id_prodi)
                // ->whereNull('id_jenis_keluar')
                ->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('bak.monitoring.pengisian-krs.detail-aktif-min-tujuh', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_isi_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new RiwayatPendidikan();

        $data = $db->detail_isi_krs($id_prodi, $semesterAktif);

        return view('bak.monitoring.pengisian-krs.detail-isi-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_approved_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;
        $db = new RiwayatPendidikan();

        $data = $db->krs_data($id_prodi, $semesterAktif, 1);

        return view('bak.monitoring.pengisian-krs.approve-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function detail_not_approved_krs(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;
        $db = new RiwayatPendidikan();
        $data = $db->krs_data($id_prodi, $semesterAktif, 0);

        return view('bak.monitoring.pengisian-krs.not-approve-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function tidak_isi_krs(ProgramStudi $prodi)
    {

        $id_prodi = $prodi->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new RiwayatPendidikan();
        $data = $db->tidak_isi_krs($id_prodi, $semesterAktif);

        return view('bak.monitoring.pengisian-krs.tidak-isi-krs', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function mahasiswa_up_tujuh(ProgramStudi $prodi)
    {
        $id_prodi = $prodi->id_prodi;

        $angkatanAktif = date('Y') - 7;
        $arrayTahun = range($angkatanAktif, date('Y'));

        $data = RiwayatPendidikan::whereDoesntHave('lulus_do')
                ->where('id_prodi', $id_prodi)
                // ->whereNull('id_jenis_keluar')
                ->whereNotIn(DB::raw('LEFT(id_periode_masuk, 4)'), $arrayTahun)
                ->orderBy('id_periode_masuk', 'ASC')
                ->get();

        return view('bak.monitoring.pengisian-krs.mahasiswa-up-tujuh', [
            'prodi' => $prodi,
            'data' => $data
        ]);
    }

    public function lulus_do(Request $request)
    {
        $db = new LulusDo();
        $jenis_keluar = $db->select('id_jenis_keluar', 'nama_jenis_keluar')->distinct()->get();

        $jenis_keluar_counts = $db->select('id_jenis_keluar','nama_jenis_keluar', DB::raw('count(*) as total'))
        ->groupBy('id_jenis_keluar','nama_jenis_keluar');

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $jenis_keluar_counts->whereIn('id_prodi', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $jenis_keluar_counts->whereIn('angkatan', $filter);
        }

        if($request->has('jenis_keluar') && !empty($request->jenis_keluar)) {
            $filter = $request->jenis_keluar;
            $jenis_keluar_counts->whereIn('id_jenis_keluar', $filter);
        }

        $jenis_keluar_counts = $jenis_keluar_counts->get();

        $prodi = ProgramStudi::orderBy('kode_program_studi')->get();
        $angkatan = $db->select('angkatan')->distinct()->orderBy('angkatan', 'desc')->get();

        return view('bak.monitoring.kelulusan.index', [
            'jenis_keluar' => $jenis_keluar,
            'jenis_keluar_counts' => $jenis_keluar_counts,
            'prodi' => $prodi,
            'angkatan' => $angkatan
        ]);
    }

    public function lulus_do_data(Request $request)
    {
        $searchValue = $request->input('search.value');

        $query = LulusDo::with('prodi', 'biodata', 'periode_keluar');

        if ($searchValue) {
            $query = $query->where('nim', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_mahasiswa', 'like', '%' . $searchValue . '%')
                ->orWhere('nama_program_studi', 'like', '%' . $searchValue . '%');
        }

        if ($request->has('id_prodi') && !empty($request->id_prodi)) {
            $filter = $request->id_prodi;
            $query->whereIn('id_prodi', $filter);
        }

        if ($request->has('angkatan') && !empty($request->angkatan)) {
            $filter = $request->angkatan;
            $query->whereIn('angkatan', $filter);
        }

        if($request->has('jenis_keluar') && !empty($request->jenis_keluar)) {
            $filter = $request->jenis_keluar;
            $query->whereIn('id_jenis_keluar', $filter);
        }

        $recordsFiltered = $query->count();

        $limit = $request->input('length');
        $offset = $request->input('start');

        // Define the column names that correspond to the DataTables column indices
        if ($request->has('order')) {
            $orderColumn = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');

            // Define the column names that correspond to the DataTables column indices
            $columns = ['nim','nama_mahasiswa', 'nama_program_studi', 'angkatan', 'tanggal_keluar', 'nm_smt', 'keterangan'];

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

        $recordsTotal = LulusDo::count();

        $response = [
            'draw' => $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return response()->json($response);
    }

    public function pengisian_nilai()
    {
        $fakultas = Fakultas::select('id', 'nama_fakultas')->get();
        $prodi = ProgramStudi::where('status', 'A')
                        ->select('id_prodi', 'nama_program_studi','kode_program_studi', 'nama_jenjang_pendidikan', 'fakultas_id', 'id')
                        ->orderBy('id_jenjang_pendidikan', 'ASC')
                        ->orderBy('nama_program_studi')
                        ->get();

        $semesterAktif = SemesterAktif::first()->id_semester;
        $semester = Semester::where('id_semester', '<=', $semesterAktif)->orderBy('id_semester', 'desc')->get();

        return view('bak.monitoring.pengisian-nilai.index', [
            'fakultas' => $fakultas,
            'prodi' => $prodi,
            'semester' => $semester,
            'semesterAktif' => $semesterAktif
        ]);
    }

    public function pengisian_nilai_detail(string $mode, string $dosen, string $prodi)
    {
        $semesterAktif = SemesterAktif::first()->id_semester;
        $id_prodi = ProgramStudi::find($prodi)->id_prodi;
        $biodataDosen = BiodataDosen::where('id_dosen', $dosen)
                ->select('nidn', 'nama_dosen')
                ->first();

        $db = new DosenPengajarKelasKuliah();

        $titles = [
            1 => "Total Kelas Ajar",
            2 => "Kelas Sudah Dinilai",
            3 => "Kelas Belum Dinilai"
        ];

        $title = $titles[$mode] ?? "Unknown Mode";

        $query = $db->join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')
            ->join('biodata_dosens as d', 'd.id_dosen', 'dosen_pengajar_kelas_kuliahs.id_dosen')
            ->where('k.id_semester', $semesterAktif)
            ->where('dosen_pengajar_kelas_kuliahs.urutan', 1)
            ->where('k.id_prodi', $id_prodi)
            ->where('d.id_dosen', $dosen)
            ->whereRaw('EXISTS (SELECT * FROM peserta_kelas_kuliahs WHERE peserta_kelas_kuliahs.id_kelas_kuliah = k.id_kelas_kuliah AND peserta_kelas_kuliahs.approved = 1)');

        if ($mode == 2) {
            $query->whereRaw('EXISTS (SELECT 1 FROM nilai_perkuliahans WHERE nilai_perkuliahans.id_kelas_kuliah = k.id_kelas_kuliah AND nilai_perkuliahans.nilai_huruf IS NOT NULL)');
        } elseif ($mode == 3) {
            $query->whereRaw('NOT EXISTS (SELECT 1 FROM nilai_perkuliahans WHERE nilai_perkuliahans.id_kelas_kuliah = k.id_kelas_kuliah AND nilai_perkuliahans.nilai_huruf IS NOT NULL)');
        }

        $id_kelas = $query->select('k.id_kelas_kuliah')
                    ->distinct()
                    ->pluck('k.id_kelas_kuliah');

        $data = KelasKuliah::whereIn('id_kelas_kuliah', $id_kelas)
            ->with(['matkul', 'prodi', 'dosen_pengajar','peserta_kelas' => function ($query) {
                $query->where('approved', 1);
            }])
            ->get();

        return view('bak.monitoring.pengisian-nilai.detail', [
            'title' => $title,
            'data' => $data,
            'dosen' => $biodataDosen,
        ]);

    }

    public function pengisian_nilai_data(Request $request)
    {
        $prodi = $request->input('prodi');
        $id_prodi = ProgramStudi::find($prodi)->id_prodi;
        $semesterAktif = SemesterAktif::first()->id_semester;
        $semester = $semesterAktif;

        $db = new DosenPengajarKelasKuliah();

        // Query untuk mendapatkan data dosen dan jumlah kelas yang dinilai dan belum dinilai
        $data = $db->join('kelas_kuliahs as k', 'k.id_kelas_kuliah', 'dosen_pengajar_kelas_kuliahs.id_kelas_kuliah')
            ->join('biodata_dosens as d', 'd.id_dosen', 'dosen_pengajar_kelas_kuliahs.id_dosen')
            ->where('k.id_semester', $semester)
            ->where('dosen_pengajar_kelas_kuliahs.urutan', 1)
            ->where('k.id_prodi', $id_prodi)
            ->whereRaw('EXISTS (SELECT * FROM peserta_kelas_kuliahs WHERE peserta_kelas_kuliahs.id_kelas_kuliah = k.id_kelas_kuliah AND peserta_kelas_kuliahs.approved = 1)')
            ->select(
                'dosen_pengajar_kelas_kuliahs.id_dosen',
                'd.nidn',
                'd.nama_dosen',
                DB::raw('COUNT(k.id_kelas_kuliah) as total_kelas'),
                DB::raw('SUM(CASE WHEN EXISTS (SELECT 1 FROM nilai_perkuliahans WHERE nilai_perkuliahans.id_kelas_kuliah = k.id_kelas_kuliah AND nilai_perkuliahans.nilai_huruf IS NOT NULL) THEN 1 ELSE 0 END) as total_kelas_dinilai')
            )
            ->groupBy('dosen_pengajar_kelas_kuliahs.id_dosen', 'd.nidn', 'd.nama_dosen')
            ->get();

        // Proses data untuk menghitung total kelas yang belum dinilai
        $dataAccumulation = $data->map(function ($item) {
            $item->total_kelas_belum_dinilai = $item->total_kelas - $item->total_kelas_dinilai;
            return $item;
        });

        $response = [
            'status' => 'success',
            'data' => $dataAccumulation
        ];

        return response()->json($response);
    }

    public function status_mahasiswa()
    {
        $prodi = ProgramStudi::where('status', 'A')->orderBy('id')->get();
        $semesterAktif = SemesterAktif::first()->id_semester;

        $db = new MonevStatusMahasiswa();

        $data = $db->with(['prodi.fakultas', 'details', 'semester'])->where('id_semester', $semesterAktif)->get();

        return view('bak.monitoring.status-mahasiswa.index', [
            'data' => $data,
            'prodi' => $prodi
        ]);
    }

    public function detail_total_status_mahasiswa($semester, $status)
    {

        $data = MonevStatusMahasiswaDetail::whereHas('monevStatusMahasiswa', function ($query) use ($semester) {
            $query->where('id_semester', $semester);
        })->where('status', $status)->get();


        return view('bak.monitoring.status-mahasiswa.detail-total', [
            'data' => $data,
            'status' => $status
        ]);
    }

    public function detail_prodi_status_mahasiswa(int $id, string $status)
    {
        $data = MonevStatusMahasiswaDetail::where('monev_status_mahasiswa_id', $id)
                ->where('status', $status)
                ->with(['riwayat.prodi', 'riwayat.periode_masuk'])
                ->with(['riwayat.transkrip_mahasiswa' => function ($query) {
                    $query->whereNotIn('nilai_huruf', ['F', '']);
                }])
                ->get();

                $data = MonevStatusMahasiswaDetail::where('monev_status_mahasiswa_id', $id)
                ->where('status', $status)
                ->with(['riwayat.prodi', 'riwayat.periode_masuk'])
                ->with(['riwayat.transkrip_mahasiswa' => function ($query) {
                    $query->whereNotIn('nilai_huruf', ['F', '']);
                }])
                ->get();

        foreach ($data as $d) {
            $total_sks = 0;
            $ipk = 0;
            $masa_studi = 0;


            if ($d->riwayat->transkrip_mahasiswa->count() > 0) {

                $total_sks = $d->riwayat->transkrip_mahasiswa->sum('sks_mata_kuliah');

                $total_bobot_transkrip = 0;

                foreach ($d->riwayat->transkrip_mahasiswa as $t) {
                    $total_bobot_transkrip += $t->nilai_indeks * $t->sks_mata_kuliah;
                }

                if($total_sks > 0){
                    $ipk = $total_bobot_transkrip / $total_sks;
                }

                $d->total_sks = $total_sks;
                $d->ipk = number_format($ipk, 2);

            } else {
                $d->total_sks = 'Tidak Ada Transkrip';
                $d->ipk = 'Tidak Ada Transkrip';
            }

            $now = $d->created_at;

            // Validasi data sebelum perhitungan
            if (!isset($d->riwayat->periode_masuk->semester) || !isset($d->riwayat->periode_masuk->id_tahun_ajaran)) {
                $masa_studi = 'Data Tidak Valid';
            } else {
                // Konstanta untuk tanggal default
                $SEMESTER_1_START = '-08-01';
                $SEMESTER_2_START = '-01-01';

                // Hitung tanggal masuk berdasarkan semester
                if ($d->riwayat->periode_masuk->semester == 2) {
                    $tanggal_masuk = Carbon::parse($d->riwayat->periode_masuk->id_tahun_ajaran + 1 . $SEMESTER_2_START);
                } elseif ($d->riwayat->periode_masuk->semester == 1) {
                    $tanggal_masuk = Carbon::parse($d->riwayat->periode_masuk->id_tahun_ajaran . $SEMESTER_1_START);
                } else {
                    $masa_studi = 'Data Tidak Valid';
                }

                // Hitung masa studi jika tanggal masuk valid
                if (isset($tanggal_masuk)) {
                    $masa_studi = floor($tanggal_masuk->diffInMonths($now));
                }
            }

            // Tetapkan masa studi ke objek
            $d->masa_studi = $masa_studi;

        }

        return view('bak.monitoring.status-mahasiswa.detail-prodi', [
            'data' => $data,
            'status' => $status
        ]);
    }

    public function status_ukt(Request $request)
    {
        $fakultas = Fakultas::all();

        // Ambil fakultas yang dipilih dari request (single select)
        $filterFakultas = $request->get('fakultas');

        // Filter prodi berdasarkan fakultas (jika dipilih)
        $prodi_fak = ProgramStudi::where('status', 'A')
                    ->when($filterFakultas, function ($q) use ($filterFakultas) {
                        $q->where('fakultas_id', $filterFakultas);
                    })
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();

        $id_prodi_fak = $prodi_fak->pluck('id_prodi');

        // Ambil data angkatan berdasarkan prodi hasil filter
        $angkatan = RiwayatPendidikan::with(['prodi'])
                    ->whereIn('id_prodi', $id_prodi_fak)
                    ->select(DB::raw('LEFT(id_periode_masuk, 4) as angkatan_raw'))
                    ->distinct()
                    ->orderBy('angkatan_raw', 'desc')
                    ->get();

        return view('bak.monitoring.status-ukt.index', [
            'angkatan' => $angkatan,
            'prodi'    => $prodi_fak,
            'fakultas' => $fakultas,
            'fakultas_selected' => $filterFakultas, // bisa dipakai di view
        ]);
    }

    public function getProdi($fakultas_id)
    {
        $prodi = ProgramStudi::where('status', 'A')
                    ->where('fakultas_id', $fakultas_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();

        return response()->json($prodi);
    }


    public function status_ukt_data(Request $request)
    {
        $semesterAktif = SemesterAktif::first()->id_semester;

        $query = RiwayatPendidikan::whereDoesntHave('lulus_do')
            ->orderBy('nama_program_studi', 'ASC')
            ->orderBy('id_periode_masuk', 'desc');

        // Filter
        if ($request->filled('prodi')) {
            $query->where('id_prodi', $request->get('prodi'));
        }
        if ($request->filled('angkatan')) {
            $query->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $request->get('angkatan'));
        }

        $data = $query->get();

        // Tambahan informasi
        foreach ($data as $value) {
            $value->rm_no_test = Registrasi::where('rm_nim', $value->nim)->pluck('rm_no_test')->first();

            $value->tagihan = Tagihan::with('pembayaran')
                ->whereIn('nomor_pembayaran', [$value->rm_no_test, $value->nim])
                ->where('kode_periode', $semesterAktif)
                ->first();

            $penundaan = PenundaanBayar::where('id_registrasi_mahasiswa', $value->id_registrasi_mahasiswa)
                ->where('id_semester', $semesterAktif)
                ->first();

            $value->penundaan_bayar = $penundaan ? 1 : 0;
            $value->batas_bayar = $penundaan?->batas_bayar;

            // Status final
            if ($value->beasiswa) {
                $value->status_pembayaran_final = 'beasiswa';
            } elseif ($value->tagihan && $value->tagihan->pembayaran) {
                $tanggalBayar = $value->tagihan->pembayaran->tanggal_pembayaran ?? null;
                if ($penundaan && $value->batas_bayar && $tanggalBayar > $value->batas_bayar) {
                    $value->status_pembayaran_final = 'lunas_terlambat';
                } else {
                    $value->status_pembayaran_final = 'lunas';
                }
            } elseif ($penundaan) {
                $value->status_pembayaran_final = 'penundaan';
            } else {
                $value->status_pembayaran_final = 'belum_bayar';
            }
        }

        // Filter status bayar (opsional)
        if ($request->filled('status_bayar')) {
            $data = $data->filter(fn($item) =>
                in_array($item->status_pembayaran_final, (array) $request->get('status_bayar'))
            )->values();
        }

        return response()->json($data);
    }
}
