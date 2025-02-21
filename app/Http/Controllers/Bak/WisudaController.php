<?php

namespace App\Http\Controllers\Bak;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\PeriodeWisuda;
use App\Models\ProgramStudi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WisudaController extends Controller
{

    public function pengaturan()
    {
        $db = new PeriodeWisuda();
        $data = $db->orderBy('periode', 'desc')->get();
        $periode = $db->max('periode') + 1;

        return view('bak.wisuda.pengaturan.index', [
            'data' => $data,
            'periode' => $periode,
        ]);
    }

    public function pengaturan_store(Request $request)
    {
        $data = $request->validate([
            'periode' => 'required|integer|unique:periode_wisudas,periode',
            'tanggal_wisuda' => 'required',
            'tanggal_mulai_daftar' => 'required',
            'tanggal_akhir_daftar' => 'required',
            'is_active' => 'required|boolean',
        ]);

        if ($data['is_active']) {
            // buat semua periode wisuda yang aktif menjadi tidak aktif
            PeriodeWisuda::where('is_active', true)->update(['is_active' => false]);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar'])->gt(Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda']))) {
            return redirect()->back()->withInput()->withErrors(['tanggal_mulai_daftar' => 'Tanggal mulai daftar tidak boleh lebih besar dari tanggal wisuda']);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->gt(Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda']))) {
            return redirect()->back()->withInput()->withErrors(['tanggal_akhir_daftar' => 'Tanggal akhir daftar tidak boleh lebih besar dari tanggal wisuda']);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->lt(Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar']))) {
            return redirect()->back()->withInput()->withErrors(['tanggal_akhir_daftar' => 'Tanggal akhir daftar tidak boleh lebih kecil dari tanggal mulai daftar']);
        }

        $data['tanggal_wisuda'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda'])->format('Y-m-d');
        $data['tanggal_mulai_daftar'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar'])->format('Y-m-d');
        $data['tanggal_akhir_daftar'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->format('Y-m-d');

        PeriodeWisuda::create($data);

        return redirect()->route('bak.wisuda.pengaturan')->with('success', 'Data berhasil ditambahkan');

    }

    public function pengaturan_update(Request $request, PeriodeWisuda $periodeWisuda)
    {
        $data = $request->validate([
            'periode' => 'required|integer|unique:periode_wisudas,periode,' . $periodeWisuda->id . ',id',
            'tanggal_wisuda' => 'required',
            'tanggal_mulai_daftar' => 'required',
            'tanggal_akhir_daftar' => 'required',
            'is_active' => 'required|boolean',
        ]);

        if ($data['is_active']) {
            PeriodeWisuda::where('is_active', true)->update(['is_active' => false]);
        }


        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar'])->gt(Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda']))) {
            return redirect()->back()->withErrors(['tanggal_mulai_daftar' => 'Tanggal mulai daftar tidak boleh lebih besar dari tanggal wisuda']);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->gt(Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda']))) {
            return redirect()->back()->withErrors(['tanggal_akhir_daftar' => 'Tanggal akhir daftar tidak boleh lebih besar dari tanggal wisuda']);
        }

        if (Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->lt(Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar']))) {
            return redirect()->back()->withErrors(['tanggal_akhir_daftar' => 'Tanggal akhir daftar tidak boleh lebih kecil dari tanggal mulai daftar']);
        }

        $data['tanggal_wisuda'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_wisuda'])->format('Y-m-d');
        $data['tanggal_mulai_daftar'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_mulai_daftar'])->format('Y-m-d');
        $data['tanggal_akhir_daftar'] = Carbon::createFromFormat('d-m-Y', $data['tanggal_akhir_daftar'])->format('Y-m-d');

        $periodeWisuda->update($data);

        return redirect()->route('bak.wisuda.pengaturan')->with('success', 'Data berhasil diubah');
    }

    public function pengaturan_delete(PeriodeWisuda $periodeWisuda)
    {
        $periodeWisuda->delete();

        return redirect()->route('bak.wisuda.pengaturan')->with('success', 'Data berhasil dihapus');
    }

    public function peserta()
    {
        $fakultas = Fakultas::select('id','nama_fakultas')->get();
        $prodi = ProgramStudi::where('status', 'A')->get();
        $periode = PeriodeWisuda::select('periode')->get();
        return view('bak.wisuda.peserta.index', [
            'fakultas' => $fakultas,
            'prodi' => $prodi,
            'periode' => $periode,
        ]);
    }

    public function peserta_data(Request $request)
    {
        $request->validate([
            'periode' => 'required',
            'fakultas' => 'required',
            'prodi' => 'required',
        ]);
    }

    public function registrasi_ijazah(Request $request)
    {
        return view('bak.wisuda.registrasi-ijazah.index');
    }

    public function ijazah(Request $request)
    {
        return view('bak.wisuda.ijazah.index');
    }

    public function transkrip(Request $request)
    {
        return view('bak.wisuda.transkrip.index');
    }

    public function album(Request $request)
    {
        return view('bak.wisuda.album.index');
    }

    public function usept(Request $request)
    {
        return view('bak.wisuda.usept.index');
    }
}
