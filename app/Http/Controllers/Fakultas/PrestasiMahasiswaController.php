<?php

namespace App\Http\Controllers\Fakultas;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class PrestasiMahasiswaController extends Controller
{
    public function belmawa_index(Request $request)
    {
        $id_fakultas = auth()->user()->fk_id;
        $tahun_view = $request->get('tahun_view');
        $prodi_view = $request->get('prodi_view');

        // 1. Ambil daftar Program Studi di bawah Fakultas ini
        $pilihan_prodi = ProgramStudi::where('fakultas_id', $id_fakultas)
            ->where('status', 'A')
            ->orderBy('id_jenjang_pendidikan')
            ->orderBy('nama_program_studi')
            ->get();

        $array_id_prodi = $pilihan_prodi->pluck('id_prodi')->toArray();

        // 2. Ambil daftar tahun prestasi unik untuk Belmawa
        $pilihan_tahun = PrestasiMahasiswa::whereHas('riwayat_pendidikan', function ($q) use ($array_id_prodi) {
                $q->whereIn('id_prodi', $array_id_prodi);
            })
            ->where('penyelenggara', 'Belmawa')
            ->select('tahun_prestasi')
            ->distinct()
            ->orderBy('tahun_prestasi', 'DESC')
            ->pluck('tahun_prestasi');

        $tahun_aktif = $pilihan_tahun->first() ?? date('Y');
        $selected_tahun = $tahun_view ?? $tahun_aktif;

        // 3. Base Query Prestasi Belmawa
        $query = PrestasiMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.prodi'])
            ->whereHas('riwayat_pendidikan', function ($q) use ($array_id_prodi, $prodi_view) {
                if ($prodi_view && $prodi_view !== 'all') {
                    $q->where('id_prodi', $prodi_view);
                } else {
                    $q->whereIn('id_prodi', $array_id_prodi);
                }
            })
            ->where('penyelenggara', 'Belmawa');

        // Filter Tahun
        if ($selected_tahun !== 'all') {
            $query->where('tahun_prestasi', $selected_tahun);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();

        return view('fakultas.data-prestasi.prestasi-belmawa', compact(
            'data',
            'pilihan_prodi',
            'pilihan_tahun',
            'tahun_view',
            'prodi_view',
            'tahun_aktif'
        ));
    }

    public function belmawa_approve($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 1) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Fakultas.');
        }

        $prestasi->update([
            'approved' => 2
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil disetujui Tingkat Fakultas.');
    }

    public function belmawa_decline(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500'
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.'
        ]);

        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 1) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Fakultas.');
        }

        $prestasi->update([
            'approved'          => 98,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil ditolak/dibatalkan Tingkat Fakultas.');
    }

    public function mandiri_index(Request $request)
    {
        $id_fakultas = auth()->user()->fk_id;
        $tahun_view = $request->get('tahun_view');
        $prodi_view = $request->get('prodi_view');

        // 1. Ambil daftar Program Studi di bawah Fakultas ini
        $pilihan_prodi = ProgramStudi::where('fakultas_id', $id_fakultas)
            ->where('status', 'A')
            ->orderBy('id_jenjang_pendidikan')
            ->orderBy('nama_program_studi')
            ->get();

        $array_id_prodi = $pilihan_prodi->pluck('id_prodi')->toArray();

        // 2. Ambil daftar tahun prestasi unik untuk Mandiri (Non-Belmawa)
        $pilihan_tahun = PrestasiMahasiswa::whereHas('riwayat_pendidikan', function ($q) use ($array_id_prodi) {
                $q->whereIn('id_prodi', $array_id_prodi);
            })
            ->where('penyelenggara', '!=', 'Belmawa')
            ->select('tahun_prestasi')
            ->distinct()
            ->orderBy('tahun_prestasi', 'DESC')
            ->pluck('tahun_prestasi');

        $tahun_aktif = $pilihan_tahun->first() ?? date('Y');
        $selected_tahun = $tahun_view ?? $tahun_aktif;

        // 3. Base Query Prestasi Mandiri
        $query = PrestasiMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.prodi'])
            ->whereHas('riwayat_pendidikan', function ($q) use ($array_id_prodi, $prodi_view) {
                if ($prodi_view && $prodi_view !== 'all') {
                    $q->where('id_prodi', $prodi_view);
                } else {
                    $q->whereIn('id_prodi', $array_id_prodi);
                }
            })
            ->where('penyelenggara', '!=', 'Belmawa');

        // Filter Tahun
        if ($selected_tahun !== 'all') {
            $query->where('tahun_prestasi', $selected_tahun);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();

        return view('fakultas.data-prestasi.prestasi-mandiri', compact(
            'data',
            'pilihan_prodi',
            'pilihan_tahun',
            'tahun_view',
            'prodi_view',
            'tahun_aktif'
        ));
    }

    public function mandiri_approve($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 1) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Fakultas.');
        }

        $prestasi->update([
            'approved' => 2
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil disetujui Tingkat Fakultas.');
    }

    public function mandiri_decline(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500'
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.'
        ]);

        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 1) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Fakultas.');
        }

        $prestasi->update([
            'approved'          => 98,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil ditolak/dibatalkan Tingkat Fakultas.');
    }
}