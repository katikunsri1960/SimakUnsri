<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\Referensi\PredikatKelulusan;
use Illuminate\Http\Request;
use App\Models\Fakultas;
use App\Models\Semester;
use App\Models\ProgramStudi;
use App\Models\SemesterAktif;
use App\Models\PenundaanBayar;
use App\Models\MonitoringIsiKrs;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Connection\Tagihan;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Models\Connection\Registrasi;
use App\Models\Perkuliahan\KelasKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Monitoring\MonevStatusMahasiswa;
use App\Models\Perkuliahan\DosenPengajarKelasKuliah;
use App\Models\Monitoring\MonevStatusMahasiswaDetail;

class DataMasterController extends Controller
{
    public function predikat()
    {
        $data = PredikatKelulusan::all();
        return view('bak.data-master.predikat.index', [
            'data' => $data,
        ]);
    }

    public function predikat_store(Request $request)
    {
        $data = $request->validate([
            'indonesia' => 'required',
            'inggris' => 'required',
        ]);

        PredikatKelulusan::create($data);

        return redirect()->route('bak.data-master.predikat')->with('success', 'Data berhasil disimpan');
    }

    public function predikat_update(Request $request, PredikatKelulusan $predikat)
    {
        $data = $request->validate([
            'indonesia' => 'required',
            'inggris' => 'required',
        ]);

        $predikat->update($data);

        return redirect()->route('bak.data-master.predikat')->with('success', 'Data berhasil diupdate');
    }

    public function predikat_delete(PredikatKelulusan $predikat)
    {
        $predikat->delete();

        return redirect()->route('bak.data-master.predikat')->with('success', 'Data berhasil dihapus');
    }

    //DATA MAHASISWA
    public function getProdi($fakultas_id)
    {
        $prodi = ProgramStudi::where('status', 'A')
                    ->where('fakultas_id', $fakultas_id)
                    ->orderBy('id_jenjang_pendidikan')
                    ->orderBy('nama_program_studi')
                    ->get();

        return response()->json($prodi);
    }

    public function mahasiswa(Request $request)
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

        return view('bak.data-master.mahasiswa.index', [
            'angkatan' => $angkatan,
            'prodi'    => $prodi_fak,
            'fakultas' => $fakultas,
            'fakultas_selected' => $filterFakultas, // bisa dipakai di view
        ]);
    }

    public function mahasiswa_data(Request $request)
    {
        $semesterAktif = SemesterAktif::first()->id_semester;

        $query = RiwayatPendidikan::with('biodata', 'lulus_do', 'beasiswa', 'beasiswa.jenis_beasiswa', 'prodi', 'jalur_masuk')
            ->orderBy('nama_program_studi', 'ASC')
            ->orderBy('id_periode_masuk', 'desc');

        // Filter
        if ($request->filled('prodi')) {
            $query->where('id_prodi', $request->get('prodi'));
        }
        if ($request->filled('angkatan')) {
            $query->whereIn(DB::raw('LEFT(id_periode_masuk, 4)'), $request->get('angkatan'));
        }

        $data = $query
                        // ->orderBy('nim', 'ASC')
                        // ->limit(10)
                        ->get();

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

        return response()->json($data);
    }
}
