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
    /**
     * DataTables AJAX untuk kehadiran mahasiswa (dengan JOIN untuk hindari N+1 query).
     */
    public function kehadiran_mahasiswa_ajax()
    {
        $data = DB::table('kehadiran_mahasiswa as km')
            ->leftJoin('biodata_dosens as bd', function ($join) {
                $join->on('bd.nip', '=', 'km.deskripsi_sesi')
                    ->orOn('bd.nuptk', '=', 'km.deskripsi_sesi')
                    ->orOn('bd.nidn', '=', 'km.deskripsi_sesi');
            })
            ->leftJoin('riwayat_pendidikans as rp', 'rp.nim', '=', 'km.username')
            ->select([
                'km.*',
                'bd.nama_dosen',
                'rp.nama_mahasiswa'
            ]);

        return DataTables::of($data)
            ->addColumn('nama_dosen', function ($item) {
                if (!$item->deskripsi_sesi) {
                    return 'Deskripsi Tidak Ditulis';
                }
                return $item->nama_dosen ?? 'N/A';
            })
            ->addColumn('nama_mahasiswa', function ($item) {
                if (!$item->username) {
                    return 'Deskripsi Tidak Ditulis';
                }
                return $item->nama_mahasiswa ?? 'N/A';
            })
            ->editColumn('session_date', function ($item) {
                return $item->session_date
                    ? Carbon::createFromTimestamp($item->session_date)->format('d-m-Y')
                    : 'Tanggal Tidak Tersedia';
            })
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * DataTables AJAX untuk mata kuliah e-learning (dioptimasi dengan select eksplisit).
     */
    public function mk_elearning_ajax()
    {
        $data = mk_kelas::query()
            ->select('*');  // Eksplisit select untuk efisiensi (hindari * implicit)
        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
    /**
     * Menampilkan daftar kehadiran dosen (view blade kosong untuk DataTables).
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
        $data = DB::table('kehadiran_dosen as kd')  //
            ->leftJoin('biodata_dosens as bd', function ($join) {
                $join->on('bd.nip', '=', 'kd.deskripsi_sesi')
                    ->orOn('bd.nuptk', '=', 'kd.deskripsi_sesi')
                    ->orOn('bd.nidn', '=', 'kd.deskripsi_sesi');
            })
            ->select([
                'kd.*',  // Semua kolom kehadiran dosen
                'bd.nama_dosen'
            ]);
        return DataTables::of($data)
            ->addColumn('nama_dosen', function ($item) {
                if (!$item->deskripsi_sesi) {
                    return 'Deskripsi Tidak Ditulis';
                }
                return $item->nama_dosen ?? 'N/A';
            })
            ->editColumn('session_date', function ($item) {
                return $item->session_date
                    ? Carbon::createFromTimestamp($item->session_date)->format('d-m-Y')
                    : 'Tanggal Tidak Tersedia';
            })
            ->addIndexColumn()
            ->make(true);
    }
    public function realisasi_pertemuan()
    {
        return view('universitas.perkuliahan.kehadiran.realisasi-pertemuan');
    }


    // Menyediakan data JSON untuk DataTables (AJAX)
    public function realisasi_pertemuan_ajax()
    {
        // Ambil semua ID kelas dari tabel kehadiran_dosen
        $kelas_ids = DB::table('kehadiran_dosen')
            ->select('id_kelas_kuliah')
            ->distinct()
            ->pluck('id_kelas_kuliah');

        // Ambil data dosen dan kelas yang cocok
        $data = DB::table('dosen_pengajar_kelas_kuliahs as dpk')
            ->join('kelas_kuliahs as kk', 'kk.id_kelas_kuliah', '=', 'dpk.id_kelas_kuliah')
            ->join('matkul_kurikulums as mk', 'kk.id_matkul', '=', 'mk.id_matkul')
            ->join('biodata_dosens as dosen', 'dpk.id_dosen', '=', 'dosen.id_dosen') // << tambah join ini
            ->whereIn('dpk.id_kelas_kuliah', $kelas_ids)
            ->select(
                'dosen.nama_dosen',
                'dpk.id_kelas_kuliah',
                'mk.nama_mata_kuliah as nama_mata_kuliah',
                'kk.nama_kelas_kuliah as nama_kelas_kuliah',
                'dpk.rencana_minggu_pertemuan',
                'dpk.realisasi_minggu_pertemuan'
            )
            ->distinct();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
