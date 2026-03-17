<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgramStudi;
use App\Models\PeriodeWisuda;
use App\Models\Wisuda;
use App\Models\Fakultas;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\SKPI;
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPIBidangKegiatan;
use Illuminate\Support\Facades\DB;
use DateTime;

class SKPIController extends Controller
{
    public function index(Request $request)
    {
        $fakultas = Fakultas::select('id','nama_fakultas')->get();
        
        $prodi = ProgramStudi::where('status', 'A')->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi', 'fakultas_id')
                    ->orderBy('id_jenjang_pendidikan', 'ASC')
                    ->orderBy('kode_program_studi')
                    ->orderBy('nama_program_studi', 'ASC')
                    ->get();

        $periode = PeriodeWisuda::select('periode')
                ->orderBy('periode', 'desc')
                ->get();

        return view('bak.wisuda.data-skpi.index', [
            'prodi' => $prodi, 'periode' => $periode, 'fakultas' => $fakultas]);
    }

    public function skpi_data(Request $request)
    {
        // Validasi
        $req = $request->validate([
            'periode' => 'required',
            'fakultas' => [
            'required',
                function ($attribute, $value, $fail) {
                    if ($value !== '*' && !Fakultas::where('id', $value)->exists()) {
                    $fail('Fakultas tidak valid.');
                    }
                },
            ],
            'prodi' => [
            'required',
                function ($attribute, $value, $fail) {
                    if ($value !== '*' && !ProgramStudi::where('id_prodi', $value)->exists()) {
                    $fail('Program Studi tidak valid.');
                    }
                },
            ],
        ]);

        // $data = Wisuda::with('riwayat_pendidikan.skpi')
        //         ->whereHas('riwayat_pendidikan.skpi' , function($query) {
        //                 $query->whereNotNull('id_registrasi_mahasiswa');
        //             })
        //         ->leftJoin('program_studis as p', 'p.id_prodi', 'data_wisuda.id_prodi')
        //         ->leftJoin('periode_wisudas as pw', 'pw.periode', 'data_wisuda.wisuda_ke')
        //         // ->leftJoin('skpi_data as skpi', 'skpi.id_registrasi_mahasiswa', 'data_wisuda.id_registrasi_mahasiswa')
        //         ->leftJoin('fakultas as f', 'f.id', 'p.fakultas_id')
        //         ->where('pw.periode', $req['periode'])
        //         // ->where('p.fakultas_id', auth()->user()->fk_id)

        //         ->groupBy(
        //             'data_wisuda.id',
        //             'data_wisuda.id_registrasi_mahasiswa',
        //             'data_wisuda.nim',
        //             'data_wisuda.nama_mahasiswa',
        //             'p.nama_program_studi',
        //             'p.nama_jenjang_pendidikan',
        //             'f.nama_fakultas'
        //         )

        //         ->select(
        //             'data_wisuda.id',
        //             'data_wisuda.nim',
        //             'data_wisuda.nama_mahasiswa',
        //             'p.nama_program_studi as nama_prodi',
        //             'p.nama_jenjang_pendidikan as jenjang',
        //             'f.nama_fakultas',

        //             DB::raw("
        //                 CASE
        //                     WHEN MAX(riwayat_pendidikan.skpi.approved) = 3 AND MIN(riwayat_pendidikan.skpi.approved) = 3 THEN 'Disetujui Dir Akademik'
        //                     WHEN MAX(riwayat_pendidikan.skpi.approved) = 2 AND MIN(riwayat_pendidikan.skpi.approved) = 2 THEN 'Disetujui Fakultas'
        //                     WHEN MAX(riwayat_pendidikan.skpi.approved) = 1 AND MIN(riwayat_pendidikan.skpi.approved) = 1 THEN 'Disetujui Koor Prodi'
        //                     ELSE 'Belum Disetujui'
        //                 END as approved
        //             ")
        //         );

        $data = Wisuda::query()
            ->leftJoin('program_studis as p', 'p.id_prodi', '=', 'data_wisuda.id_prodi')
            ->leftJoin('periode_wisudas as pw', 'pw.periode', '=', 'data_wisuda.wisuda_ke')
            ->leftJoin('skpi_data as skpi', 'skpi.id_registrasi_mahasiswa', '=', 'data_wisuda.id_registrasi_mahasiswa')
            ->leftJoin('fakultas as f', 'f.id', '=', 'p.fakultas_id')

            ->where('pw.periode', $req['periode'])
            ->whereNotNull('skpi.id_registrasi_mahasiswa')

            ->select(
                'data_wisuda.id',
                'data_wisuda.nim',
                'data_wisuda.nama_mahasiswa',
                'p.nama_program_studi as nama_prodi',
                'p.nama_jenjang_pendidikan as jenjang',
                'f.nama_fakultas',

                DB::raw("
                    CASE
                        WHEN MAX(skpi.approved) = 3 AND MIN(skpi.approved) = 3 THEN 'Disetujui Dir Akademik'
                        WHEN MAX(skpi.approved) = 2 AND MIN(skpi.approved) = 2 THEN 'Disetujui Fakultas'
                        WHEN MAX(skpi.approved) = 1 AND MIN(skpi.approved) = 1 THEN 'Disetujui Koor Prodi'
                        ELSE 'Belum Disetujui'
                    END as approved
                ")
            )

            ->groupBy(
                'data_wisuda.id',
                'data_wisuda.nim',
                'data_wisuda.nama_mahasiswa',
                'p.nama_program_studi',
                'p.nama_jenjang_pendidikan',
                'f.nama_fakultas');

        if ($req['prodi'] != "*") {
            $data->where('p.id_prodi', $req['prodi']);
        }

        if ($req['fakultas'] != "*") {
            $data->where('p.fakultas_id', $req['fakultas']);
        }

        $data = $data->get();

        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
                'data' => [],
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diambil',
            'data' => $data,
        ]);
    }

    public function approve_skpi($id)
    {
        $skpi = SKPI::find($id);

        if (!$skpi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $skpi->update([
            'approved' => 3
        ]);

        session()->flash('success','Data berhasil disimpan');

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function decline_skpi(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:255'
        ]);

        $skpi = SKPI::find($id);

        if(!$skpi){
            return response()->json([
                'status'=>'error'
            ]);
        }

        $skpi->update([
            'approved' => 99,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        session()->flash('success','Data berhasil disimpan');

        return response()->json([
            'status'=>'success'
        ]);
    }

    public function detail_skpi_mahasiswa($id)
    {
        // dd($id);
        $mahasiswa = Wisuda::leftJoin('program_studis as p', 'p.id_prodi', '=', 'data_wisuda.id_prodi')
            ->where('data_wisuda.id', $id)
            // ->select(
            //     'data_wisuda.id_registrasi_mahasiswa',
            //     'data_wisuda.nim',
            //     'data_wisuda.nama_mahasiswa',
            //     'p.nama_program_studi',
            //     'p.nama_jenjang_pendidikan'
            // )
            ->first();

        $bidang = SKPIBidangKegiatan::get();

        // dd($bidang);

        $data = SKPI::leftJoin('skpi_jenis_kegiatan as jk', 'jk.id', '=', 'skpi_data.id_jenis_skpi')
            ->leftJoin('skpi_bidang_kegiatan as bk', 'bk.id', '=', 'jk.bidang_id')
            ->where('skpi_data.id_registrasi_mahasiswa', $mahasiswa->id_registrasi_mahasiswa)
            ->select(
                'skpi_data.*',
                'jk.nama_jenis',
                'jk.kriteria',
            )
            ->get();

        $total_skor = SKPI::where('id_registrasi_mahasiswa', $mahasiswa->id_registrasi_mahasiswa)
                ->sum('skor');
            // dd($total_skor);

        return view('bak.wisuda.data-skpi.detail', 
            compact('mahasiswa', 'bidang','data', 'total_skor'));
    }

}
