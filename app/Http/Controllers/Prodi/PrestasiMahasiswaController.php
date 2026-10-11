<?php

namespace App\Http\Controllers\Prodi;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\PrestasiMahasiswa;
use Illuminate\Http\Request;

class PrestasiMahasiswaController extends Controller
{
    public function belmawa_index(Request $request)
    {
        $id_prodi = auth()->user()->fk_id;
        $tahun_view = $request->get('tahun_view');

        // Ambil daftar tahun prestasi unik
        $pilihan_tahun = PrestasiMahasiswa::whereHas('riwayat_pendidikan', function ($q) use ($id_prodi) {
                $q->where('id_prodi', $id_prodi);
            })
            ->select('tahun_prestasi')
            ->distinct()
            ->orderBy('tahun_prestasi', 'DESC')
            ->pluck('tahun_prestasi');

        // Set default ke tahun terbaru jika belum ada request
        $tahun_aktif = $pilihan_tahun->first() ?? date('Y');
        $selected_tahun = $tahun_view ?? $tahun_aktif;

        // Base Query
        $query = PrestasiMahasiswa::with('riwayat_pendidikan')
            ->whereHas('riwayat_pendidikan', function ($q) use ($id_prodi) {
                $q->where('id_prodi', $id_prodi);
            })
            ->where('penyelenggara', 'Belmawa');

        // Terapkan filter tahun jika opsi yang dipilih BUKAN "all"
        if ($selected_tahun !== 'all') {
            $query->where('tahun_prestasi', $selected_tahun);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();

        return view('prodi.data-prestasi.prestasi-belmawa', compact('data', 'pilihan_tahun', 'tahun_view', 'tahun_aktif'));
    }

    public function belmawa_approve($id)
    {
        $prestasi = PrestasiMahasiswa::findOrFail($id);

        if ($prestasi->approved != 0) {
            return redirect()->back()->with('error', 'Data prestasi ini sudah diproses sebelumnya.');
        }

        $prestasi->update([
            'approved' => 1
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil disetujui Koor. Prodi.');
    }

    public function belmawa_decline(Request $request, $id)
    {
        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500'
        ], [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.'
        ]);

        $prestasi = PrestasiMahasiswa::findOrFail($id);

        // if ($prestasi->approved != 0) {
        //     return redirect()->back()->with('error', 'Data prestasi ini sudah diproses sebelumnya.');
        // }

        $prestasi->update([
            'approved'          => 97,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil ditolak/dibatalkan.');
    }

    public function mandiri_index(Request $request)
        {
            $id_prodi = auth()->user()->fk_id;
            $tahun_view = $request->get('tahun_view');

            // Ambil daftar tahun prestasi unik
            $pilihan_tahun = PrestasiMahasiswa::whereHas('riwayat_pendidikan', function ($q) use ($id_prodi) {
                    $q->where('id_prodi', $id_prodi);
                })
                ->select('tahun_prestasi')
                ->distinct()
                ->orderBy('tahun_prestasi', 'DESC')
                ->pluck('tahun_prestasi');

            // Set default ke tahun terbaru jika belum ada request
            $tahun_aktif = $pilihan_tahun->first() ?? date('Y');
            $selected_tahun = $tahun_view ?? $tahun_aktif;

            // Base Query
            $query = PrestasiMahasiswa::with('riwayat_pendidikan')
                ->whereHas('riwayat_pendidikan', function ($q) use ($id_prodi) {
                    $q->where('id_prodi', $id_prodi);
                })
                ->whereNot('penyelenggara', 'Belmawa');

            // Terapkan filter tahun jika opsi yang dipilih BUKAN "all"
            if ($selected_tahun !== 'all') {
                $query->where('tahun_prestasi', $selected_tahun);
            }

            $data = $query->orderBy('created_at', 'DESC')->get();

            return view('prodi.data-prestasi.prestasi-mandiri', compact('data', 'pilihan_tahun', 'tahun_view', 'tahun_aktif'));
        }

        public function mandiri_approve($id)
        {
            $prestasi = PrestasiMahasiswa::findOrFail($id);

            if ($prestasi->approved != 0) {
                return redirect()->back()->with('error', 'Data prestasi ini sudah diproses sebelumnya.');
            }

            $prestasi->update([
                'approved' => 1
            ]);

            return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil disetujui Koor. Prodi.');
        }

        public function mandiri_decline(Request $request, $id)
        {
            $request->validate([
                'alasan_pembatalan' => 'required|string|max:500'
            ], [
                'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi.'
            ]);

            $prestasi = PrestasiMahasiswa::findOrFail($id);

            if ($prestasi->approved != 0) {
                return redirect()->back()->with('error', 'Data prestasi ini sudah diproses sebelumnya.');
            }

            $prestasi->update([
                'approved'          => 97,
                'alasan_pembatalan' => $request->alasan_pembatalan
            ]);

            return redirect()->back()->with('success', 'Prestasi mahasiswa berhasil ditolak/dibatalkan.');
        }

        
}