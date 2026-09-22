<?php

namespace App\Http\Controllers\Prodi\Lulusan;

use DateTime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisuda;
use App\Models\SKPI;
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPIBidangKegiatan;
use App\Models\PeriodeWisuda;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Metadata\Group;

class SKPIController extends Controller
{
    public function index(Request $request)
    {
        $prodi_id = auth()->user()->fk_id;

        /*
        |--------------------------------------------------------------------------
        | PERIODE WISUDA
        |--------------------------------------------------------------------------
        */
        $periodeWisuda = PeriodeWisuda::select(
                'periode'
            )
            ->orderBy('periode', 'desc')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | FILTER PERIODE
        |--------------------------------------------------------------------------
        |
        | Jika tidak ada filter periode:
        | gunakan periode terbaru.
        |
        */
        $periodeFilter = $request->input('periode');

        if (empty($periodeFilter)) {
            $periodeFilter = optional(
                $periodeWisuda->first()
            )->periode;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA MAHASISWA
        |--------------------------------------------------------------------------
        */
        $data = Wisuda::with([
                'periode_wisuda',
                'riwayat_pendidikan.skpi',
                'bebas_pustaka',
            ])

            /*
            |--------------------------------------------------------------------------
            | Filter periode wisuda
            |--------------------------------------------------------------------------
            */
            ->when($periodeFilter, function ($query) use ($periodeFilter) {

                $query->whereHas('periode_wisuda', function ($q) use ($periodeFilter) {

                    $q->where('periode', $periodeFilter);

                });

            })

            /*
            |--------------------------------------------------------------------------
            | Hanya mahasiswa yang mempunyai data SKPI
            |--------------------------------------------------------------------------
            */
            ->whereHas('riwayat_pendidikan.skpi')

            /*
            |--------------------------------------------------------------------------
            | Hanya mahasiswa pada prodi user login
            |--------------------------------------------------------------------------
            */
            ->where('id_prodi', $prodi_id)

            ->orderBy('nim', 'asc')

            ->get();

        /*
        |--------------------------------------------------------------------------
        | HITUNG TOTAL SKOR & STATUS
        |--------------------------------------------------------------------------
        */
        $data->each(function ($d) {

            $totalSkor = 0;
            $approvedList = collect();

            $riwayat = $d->riwayat_pendidikan;

            /*
            |--------------------------------------------------------------------------
            | Pastikan riwayat pendidikan berbentuk collection
            |--------------------------------------------------------------------------
            */
            if ($riwayat instanceof \Illuminate\Database\Eloquent\Model) {
                $riwayat = collect([$riwayat]);
            } elseif (!$riwayat instanceof \Illuminate\Support\Collection) {
                $riwayat = collect();
            }

            /*
            |--------------------------------------------------------------------------
            | Hitung SKPI
            |--------------------------------------------------------------------------
            */
            foreach ($riwayat as $rp) {

                $skpiList = $rp->skpi ?? collect();

                if (!$skpiList instanceof \Illuminate\Support\Collection) {
                    $skpiList = collect([$skpiList]);
                }

                foreach ($skpiList as $skpi) {

                    /*
                    |--------------------------------------------------------------------------
                    | Simpan status approval
                    |--------------------------------------------------------------------------
                    */
                    if (!is_null($skpi->approved)) {
                        $approvedList->push(
                            (int) $skpi->approved
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Hanya data yang tidak ditolak / belum diproses
                    | yang dihitung sebagai skor
                    |--------------------------------------------------------------------------
                    */
                    if (!in_array(
                        (int) $skpi->approved,
                        [0, 97, 98, 99],
                        true
                    )) {
                        $totalSkor += (float) ($skpi->skor ?? 0);
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Tentukan status mahasiswa
            |--------------------------------------------------------------------------
            */
            if ($approvedList->isEmpty()) {

                $status = 'Belum Ada Data';

            } elseif ($approvedList->contains(99)) {

                $status = 99;

            } elseif ($approvedList->contains(98)) {

                $status = 98;

            } elseif ($approvedList->contains(97)) {

                $status = 97;

            } elseif ($approvedList->every(
                fn ($v) => $v === 3
            )) {

                $status = 3;

            } elseif ($approvedList->every(
                fn ($v) => $v === 2
            )) {

                $status = 2;

            } elseif ($approvedList->every(
                fn ($v) => $v === 1
            )) {

                $status = 1;

            } elseif ($approvedList->every(
                fn ($v) => $v === 0
            )) {

                $status = 0;

            } else {

                $status = 'Proses / Parsial';
            }

            /*
            |--------------------------------------------------------------------------
            | Tambahkan property untuk Blade
            |--------------------------------------------------------------------------
            */
            $d->total_skor = $totalSkor;
            $d->approved = $status;

            return $d;
        });

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */
        return view('prodi.data-skpi.index', [
            'data' => $data,
            'periodeWisuda' => $periodeWisuda,
            'periodeFilter' => $periodeFilter,
        ]);
    }

    public function detail_skpi_mahasiswa($id)
    { 
        
        $prodi_id = auth()->user()->fk_id;

        $wisuda = Wisuda::with(['periode_wisuda', 'riwayat_pendidikan'])
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

        // dd($data);
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
