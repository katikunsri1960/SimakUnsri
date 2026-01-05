<?php

namespace App\Http\Controllers\Universitas\Kehadiran;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Dosen\BiodataDosen;
use App\Models\User;
use App\Models\mk_kelas;
use Carbon\Carbon;
use App\Models\kehadiran_dosen;
use App\Models\kehadiran_mahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class DataKehadiranController extends Controller
{
    /**
     * Menampilkan daftar kehadiran mahasiswa (view blade kosong untuk DataTables).
     * Data di-load via AJAX untuk performa optimal (hindari ::all()).
     */
    public function kehadiran()
    {
        return view('universitas.perkuliahan.kehadiran.kehadiran-mahasiswa');
    }


    public function kehadiran_mahasiswa_ajax(Request $request)
    {
        // Jika frontend mengirim parameter 'limit' atau 'length', gunakan itu.
        // Jika tidak ada, default ambil SEMUA data (limit besar).
        $limit = $request->input('limit', $request->input('length', -1));

        $query = DB::table('kehadiran_mahasiswa as km')
            ->leftJoin('riwayat_pendidikans as rp', 'rp.nim', '=', 'km.username')

            // [OPTIMASI 1] Tetap gunakan 3 Join ini agar index database jalan (Cepat)
            ->leftJoin('biodata_dosens as d_nip', 'd_nip.nip', '=', 'km.deskripsi_sesi')
            ->leftJoin('biodata_dosens as d_nidn', 'd_nidn.nidn', '=', 'km.deskripsi_sesi')
            ->leftJoin('biodata_dosens as d_nuptk', 'd_nuptk.nuptk', '=', 'km.deskripsi_sesi')

            ->select([
                'km.kode_mata_kuliah',
                'km.nama_mk',
                'km.nama_kelas',
                'km.username',
                'km.session_id',
                'km.status_mahasiswa',
                'km.deskripsi_sesi',

                // [OPTIMASI 2] Format Tanggal langsung di SQL (Agar PHP tidak berat looping)
                DB::raw("FROM_UNIXTIME(km.session_date, '%d-%m-%Y') as session_date"),

                // [OPTIMASI 3] Pilih nama dosen langsung di SQL
                DB::raw("COALESCE(d_nip.nama_dosen, d_nidn.nama_dosen, d_nuptk.nama_dosen, 'Dosen Tidak Dikenal') as nama_dosen"),

                // Handle nama mahasiswa null
                DB::raw("COALESCE(rp.nama_mahasiswa, 'Mahasiswa Tidak Dikenal') as nama_mahasiswa")
            ])

            // Urutkan dari yang terbaru
            ->orderBy('km.session_date', 'desc');

        // [LOGIKA SHOW ENTRIES]
        // Jika user minta "All" (biasanya -1) atau tidak kirim limit, pakai get()
        if ($limit == -1 || !$limit) {
            $data = $query->get(); // Ambil SEMUA data

            // Return format standar array data
            return response()->json(['data' => $data]);
        }
        // Jika user pilih "Show 10", "Show 25" lewat dropdown frontend yang mengirim parameter
        else {
            // Gunakan paginate agar query cepat sesuai limit yang diminta user
            $data = $query->paginate($limit);
            return response()->json($data);
        }
    }

    /**
     * DataTables AJAX untuk mata kuliah e-learning (dioptimasi dengan select eksplisit).
     */
    public function mk_elearning_ajax()
    {
        $data = mk_kelas::select(
            'kode_mata_kuliah',
            'kelas_kuliah',
            'id_kelas_kuliah'
        )->get();

        return response()->json([
            'data' => $data
        ]);
    }
    /**
     * Menampilkan daftar kehadiran dosen 
     */
    public function kehadiran_dosen()
    {
        return view('universitas.perkuliahan.kehadiran.kehadiran-dosen');
    }
    /**
     * DataTables AJAX untuk kehadiran dosen (dengan JOIN untuk hindari N+1 query).
     */
    public function kehadiran_dosen_ajax()
    {
        $data = DB::table('kehadiran_dosen as kd')
            ->leftJoin('biodata_dosens as bd', function ($join) {
                $join->on('bd.nip', '=', 'kd.deskripsi_sesi')
                    ->orOn('bd.nuptk', '=', 'kd.deskripsi_sesi')
                    ->orOn('bd.nidn', '=', 'kd.deskripsi_sesi');
            })
            ->select([
                'kd.kode_mata_kuliah',
                'kd.nama_mk',
                'kd.nama_kelas',
                'kd.session_id',
                'kd.session_date',
                'bd.nama_dosen',
                'kd.deskripsi_sesi',
            ])
            ->get();

        // Format tanggal
        $data = $data->map(function ($item) {
            $item->session_date = $item->session_date
                ? \Carbon\Carbon::createFromTimestamp($item->session_date)->format('d-m-Y')
                : 'Tanggal Tidak Tersedia';

            $item->nama_dosen = $item->nama_dosen ?: 'N/A';

            return $item;
        });

        return response()->json([
            'data' => $data
        ]);
    }


    public function realisasi_pertemuan()
    {
        return view('universitas.perkuliahan.kehadiran.realisasi-pertemuan');
    }


    public function realisasi_pertemuan_ajax()
    {
        $kelas_ids = DB::table('kehadiran_dosen')
            ->distinct()
            ->pluck('id_kelas_kuliah');

        $data = DB::table('dosen_pengajar_kelas_kuliahs as dpk')
            ->join('kelas_kuliahs as kk', 'kk.id_kelas_kuliah', '=', 'dpk.id_kelas_kuliah')
            ->join('matkul_kurikulums as mk', 'kk.id_matkul', '=', 'mk.id_matkul')
            ->join('biodata_dosens as dosen', 'dpk.id_dosen', '=', 'dosen.id_dosen')
            ->whereIn('dpk.id_kelas_kuliah', $kelas_ids)
            ->select(
                'dosen.nama_dosen',
                'dpk.id_kelas_kuliah',
                'mk.nama_mata_kuliah',
                'kk.nama_kelas_kuliah',
                'dpk.rencana_minggu_pertemuan',
                'dpk.realisasi_minggu_pertemuan'
            )
            ->distinct()
            ->get();

        return response()->json(['data' => $data]);
    }
}
