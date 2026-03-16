<?php

namespace App\Http\Controllers\Prodi\Lulusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisuda;
use App\Models\SKPI;
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPIBidangKegiatan;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Support\Facades\Storage;

use DateTime;
use PHPUnit\Metadata\Group;

class SKPIController extends Controller
{
    public function index()
    {
        $prodi_id = auth()->user()->fk_id;

        $data = Wisuda::with(['periode_wisuda', 'bebas_pustaka'])
                ->whereHas('periode_wisuda', function ($query) {
                    $query->where('is_active', '=', 1);
                })
                ->where('id_prodi', $prodi_id)
                ->get();

        foreach($data as $d){
                $skpi = SKPI::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)
                ->select(
                    'data_wisuda.id',
                    'data_wisuda.nim',
                    'data_wisuda.nama_mahasiswa',
                    'p.nama_program_studi as nama_prodi',
                    'p.nama_jenjang_pendidikan as jenjang',

                    DB::raw("
                        CASE
                            WHEN MAX(skpi.approved) = 3 AND MIN(skpi.approved) = 3 THEN 'Disetujui Dir Akademik'
                            WHEN MAX(skpi.approved) = 2 AND MIN(skpi.approved) = 2 THEN 'Disetujui Fakultas'
                            WHEN MAX(skpi.approved) = 1 AND MIN(skpi.approved) = 1 THEN 'Disetujui Koor Prodi'
                            ELSE 'Belum Disetujui'
                        END as approved
                    ")
                )
                ->where('approved', '=', 1);


                $total_skor= $skpi->sum('skor');                
        }
        // dd($skpi);
        return view('prodi.data-skpi.index', ['data' => $data, 'total_skor' => $total_skor ]);
    }

    public function detail_skpi_mahasiswa($id)
    { 
        
        $prodi_id = auth()->user()->fk_id;

        $wisuda = Wisuda::with(['periode_wisuda'])
                ->whereHas('periode_wisuda', function ($query) {
                    $query->where('is_active', '=', 1);
                })
                ->where('id', $id)
                ->first();
        
        $data = SKPI::leftJoin('skpi_jenis_kegiatan', 'skpi_jenis_kegiatan.id', 'skpi_data.id_jenis_skpi')
                    ->select('skpi_data.*', 'skpi_jenis_kegiatan.bidang_id', 'skpi_jenis_kegiatan.kriteria')
                    ->where('id_registrasi_mahasiswa', $wisuda->id_registrasi_mahasiswa)
                    ->orderBy('id_semester', 'ASC')
                    ->get();
        
        $total_skor = $data->sum('skor');
        // if ($data->approved > 0) {
        //     return redirect()->back()->with('error', 'Data telah difinalisasi, perubahan data tidak diperbolehkan');
        // }
        
        // dd($wisuda, $data);
        $skpi_bidang = SKPIBidangKegiatan::all();

        // dd($skpi_data);
        $skpi_jenis_kegiatan = SKPIJenisKegiatan::all();
                    // dd($skpi_jenis_kegiatan);
        return view('prodi.data-skpi.detail', ['wisuda' => $wisuda, 'skpi_bidang' => $skpi_bidang, 'data' => $data, 'skpi_jenis_kegiatan' => $skpi_jenis_kegiatan, 'total_skor' => $total_skor]);
    }

    public function approved_ajuan(Request $request,$id)
    {
        $request->validate([
            'id_jenis_skpi' => 'required|exists:skpi_jenis_kegiatan,id',
        ]);

        $data = SKPI::findOrFail($id);

        $wisuda = Wisuda::where('id_registrasi_mahasiswa',$data->id_registrasi_mahasiswa)->first();

        // if($wisuda && $wisuda->verified_skpi == 1){
        //     return back()->with('error','Data telah difinalisasi');
        // }

        $jenis = SKPIJenisKegiatan::findOrFail($request->id_jenis_skpi);

        try {

            $data->update([
                'id_jenis_skpi' => $jenis->id,
                'nama_jenis_skpi' => $jenis->nama_jenis,
                'skor' => $jenis->skor,
                'approved' => 1
            ]);

            return back()->with('success','Data SKPI berhasil diapprove');

        } catch (\Exception $e) {

            return back()->with('error','Gagal approve data');

        }
    }

    public function decline_ajuan(Request $request,$id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required',
        ]);

        $data = SKPI::findOrFail($id);

        try {

            $data->update([
                
                'approved' => 97,
                'alasan_pembatalan' => $request->alasan_pembatalan
            ]);

            return back()->with('success','Data SKPI berhasil didecline');

        } catch (\Exception $e) {

            return back()->with('error','Gagal decline data');

        }
    }

    private function getYearMonthDifference($tanggal_masuk, $tanggal_keluar) {
        $start = new DateTime($tanggal_masuk);
        $end = new DateTime($tanggal_keluar);
        $diff = $start->diff($end);

        // Calculate total months
        $totalMonths = ($diff->y * 12) + $diff->m;

        // Convert to years and decimal months
        $years = floor($totalMonths / 12);
        $months = $totalMonths % 12;
        $decimalMonths = round($months / 12, 1); // Convert months to decimal

        return ($years + $decimalMonths);
    }
}
