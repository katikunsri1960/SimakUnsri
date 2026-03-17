<?php

namespace App\Http\Controllers\Prodi\Lulusan;

use DateTime;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisuda;
use App\Models\SKPI;
use App\Models\SKPIJenisKegiatan;
use App\Models\SKPIBidangKegiatan;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Metadata\Group;

class SKPIController extends Controller
{
    public function index()
    {
        $prodi_id = auth()->user()->fk_id;

        $data = Wisuda::with([
                'periode_wisuda',
                'riwayat_pendidikan.skpi',
                'bebas_pustaka'
            ])
            ->whereHas('periode_wisuda', function ($query) {
                $query->where('is_active', 1);
            })
            ->whereHas('riwayat_pendidikan.skpi')
            ->where('id_prodi', $prodi_id)
            ->get();

        $data->map(function ($d) {

            $total = 0;
            $approved_list = [];

            $riwayat = $d->riwayat_pendidikan;

            if (!$riwayat instanceof \Illuminate\Support\Collection) {
                $riwayat = collect([$riwayat]);
            }

            foreach ($riwayat as $rp) {
                foreach (($rp->skpi ?? []) as $skpi) {

                    // ✅ hanya hitung skor valid
                    if (!in_array($skpi->approved, [0, 97, 98, 99])) {
                        $total += $skpi->skor ?? 0;
                    }

                    $approved_list[] = $skpi->approved;
                }
            }

            // ========================
            // HITUNG STATUS APPROVED
            // ========================
            $approved_list = collect($approved_list)->filter(function ($v) {
                return !is_null($v);
            });

            if ($approved_list->isEmpty()) {
                $status = 'Belum Ada Data';
            } elseif ($approved_list->contains(99)) {
                $status = 99;
            } elseif ($approved_list->contains(98)) {
                $status = 98;
            } elseif ($approved_list->contains(97)) {
                $status = 97;
            } elseif ($approved_list->every(fn($v) => $v == 3)) {
                $status = 3;
            } elseif ($approved_list->every(fn($v) => $v == 2)) {
                $status = 2;
            } elseif ($approved_list->every(fn($v) => $v == 1)) {
                $status = 1;
            } elseif ($approved_list->every(fn($v) => $v == 0)) {
                $status = 0;
            } else {
                $status = 'Proses / Parsial';
            }

            $d->total_skor = $total;
            $d->approved = $status;

            return $d;
        });

        // dd($data);

        return view('prodi.data-skpi.index', [
            'data' => $data
        ]);
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
