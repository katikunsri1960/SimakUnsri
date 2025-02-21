<?php

namespace App\Http\Controllers\Fakultas\LainLain;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\Wisuda;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
use App\Models\Referensi\AllPt;
use App\Models\Connection\Usept;
use Illuminate\Cache\Repository;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use App\Models\Perpus\BebasPustaka;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Connection\CourseUsept;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class WisudaController extends Controller
{
    public function index(Request $request)
    {
        $id_fak = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first();

        $data = Wisuda::with('prodi', 'prodi.fakultas', 'prodi.jurusan')
                ->whereHas('prodi', function ($query) use ($id_fak) {
                    $query->where('fakultas_id', $id_fak);
                })
                ->get();

                // dd($data);
        return view('fakultas.lain-lain.wisuda.index', [
            'data' => $data,
        ]);
    }

    public function approve(Request $request, Wisuda $wisuda)
    {
        $wisuda->update([
            'approved' => 2,
            'no_sk_yudisium' => $request->no_sk_yudisium,
            'tgl_sk_yudisium' => $request->tgl_sk_yudisium,
        ]);
        
        return redirect()->back()->with('success', 'Pendaftaran Wisuda berhasil disetujui.');
    }

    public function decline(Request $request, $id)
    {
        $wisuda = Wisuda::findOrFail($id);

        $wisuda->update([
            'approved' => 98,
            'alasan_pembatalan' => $request->alasan_pembatalan ?? null,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran Wisuda berhasil dibatalkan.');
    }

}
