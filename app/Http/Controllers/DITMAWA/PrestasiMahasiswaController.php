<?php

namespace App\Http\Controllers\Ditmawa;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use Illuminate\Http\Request;

class PrestasiMahasiswaController extends Controller
{
    public function belmawa_index(Request $request)
    {
        $fakultas_view = $request->get('fakultas_view');
        $prodi_view    = $request->get('prodi_view');
        $tahun_view    = $request->get('tahun_view');

        // 1. Data Master untuk Filter
        $fakultas = Fakultas::select('id', 'nama_fakultas')->get();

        $prodi = ProgramStudi::where('status', 'A')
            ->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi', 'fakultas_id')
            ->orderBy('id_jenjang_pendidikan', 'ASC')
            ->orderBy('kode_program_studi')
            ->orderBy('nama_program_studi', 'ASC')
            ->get();

        // Ambil daftar tahun prestasi unik untuk Belmawa
        $pilihan_tahun = PrestasiMahasiswa::where('penyelenggara', 'Belmawa')
            ->select('tahun_prestasi')
            ->distinct()
            ->orderBy('tahun_prestasi', 'DESC')
            ->pluck('tahun_prestasi');

        $tahun_aktif = $pilihan_tahun->first() ?? date('Y');
        $selected_tahun = $tahun_view ?? $tahun_aktif;

        // 2. Base Query Prestasi Belmawa
        $query = PrestasiMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.prodi', 'riwayat_pendidikan.prodi.fakultas'])
            ->where('penyelenggara', 'Belmawa');

        // Filter Fakultas & Prodi melalui Riwayat Pendidikan
        if ($prodi_view && $prodi_view !== 'all') {
            $query->whereHas('riwayat_pendidikan', function ($q) use ($prodi_view) {
                $q->where('id_prodi', $prodi_view);
            });
        } elseif ($fakultas_view && $fakultas_view !== 'all') {
            $query->whereHas('riwayat_pendidikan.prodi', function ($q) use ($fakultas_view) {
                $q->where('fakultas_id', $fakultas_view);
            });
        }

        // Filter Tahun
        if ($selected_tahun !== 'all') {
            $query->where('tahun_prestasi', $selected_tahun);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();

        return view('ditmawa.data-prestasi.prestasi-belmawa', compact(
            'data',
            'fakultas',
            'prodi',
            'pilihan_tahun',
            'fakultas_view',
            'prodi_view',
            'tahun_view',
            'tahun_aktif'
        ));
    }

    public function belmawa_approve($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 2) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Ditmawa.');
        }

        $prestasi->update([
            'approved' => 3
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil disetujui Ditmawa.');
    }

    public function belmawa_decline(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500'
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.'
        ]);

        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 2) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Ditmawa.');
        }

        $prestasi->update([
            'approved'          => 99,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil ditolak/dibatalkan Ditmawa.');
    }

    public function mandiri_index(Request $request)
    {
        $fakultas_view = $request->get('fakultas_view');
        $prodi_view    = $request->get('prodi_view');
        $tahun_view    = $request->get('tahun_view');

        // 1. Data Master untuk Filter
        $fakultas = Fakultas::select('id', 'nama_fakultas')->get();

        $prodi = ProgramStudi::where('status', 'A')
            ->select('id_prodi', 'kode_program_studi', 'nama_jenjang_pendidikan', 'nama_program_studi', 'fakultas_id')
            ->orderBy('id_jenjang_pendidikan', 'ASC')
            ->orderBy('kode_program_studi')
            ->orderBy('nama_program_studi', 'ASC')
            ->get();

        // Ambil daftar tahun prestasi unik untuk Mandiri (Non-Belmawa)
        $pilihan_tahun = PrestasiMahasiswa::where('penyelenggara', '!=', 'Belmawa')
            ->select('tahun_prestasi')
            ->distinct()
            ->orderBy('tahun_prestasi', 'DESC')
            ->pluck('tahun_prestasi');

        $tahun_aktif = $pilihan_tahun->first() ?? date('Y');
        $selected_tahun = $tahun_view ?? $tahun_aktif;

        // 2. Base Query Prestasi Mandiri
        $query = PrestasiMahasiswa::with(['riwayat_pendidikan', 'riwayat_pendidikan.prodi', 'riwayat_pendidikan.prodi.fakultas'])
            ->where('penyelenggara', '!=', 'Belmawa');

        // Filter Fakultas & Prodi melalui Riwayat Pendidikan
        if ($prodi_view && $prodi_view !== 'all') {
            $query->whereHas('riwayat_pendidikan', function ($q) use ($prodi_view) {
                $q->where('id_prodi', $prodi_view);
            });
        } elseif ($fakultas_view && $fakultas_view !== 'all') {
            $query->whereHas('riwayat_pendidikan.prodi', function ($q) use ($fakultas_view) {
                $q->where('fakultas_id', $fakultas_view);
            });
        }

        // Filter Tahun
        if ($selected_tahun !== 'all') {
            $query->where('tahun_prestasi', $selected_tahun);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();

        return view('ditmawa.data-prestasi.prestasi-mandiri', compact(
            'data',
            'fakultas',
            'prodi',
            'pilihan_tahun',
            'fakultas_view',
            'prodi_view',
            'tahun_view',
            'tahun_aktif'
        ));
    }

    public function mandiri_approve($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 2) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Ditmawa.');
        }

        $prestasi->update([
            'approved' => 3
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil disetujui Ditmawa.');
    }

    public function mandiri_decline(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500'
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.'
        ]);

        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 2) {
            return redirect()->back()->with('error', 'Data prestasi ini belum/tidak bisa diproses di tingkat Ditmawa.');
        }

        $prestasi->update([
            'approved'          => 99,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil ditolak/dibatalkan Ditmawa.');
    }
}