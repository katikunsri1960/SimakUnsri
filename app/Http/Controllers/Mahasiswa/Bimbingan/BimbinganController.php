<?php

namespace App\Http\Controllers\Mahasiswa\Bimbingan;

use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Connection\Tagihan;

class BimbinganController extends Controller
{
    public function index(AktivitasMahasiswa $aktivitas, Request $request)
    {
        // $id_reg = auth()->user()->fk_id;
        $id_semester = SemesterAktif::first()->id_semester;
        $user = auth()->user();

        // Cek status pembayaran
        $tagihan = Tagihan::with('pembayaran')
            ->where('nomor_pembayaran', $user->username)
            ->where('kode_periode', $id_semester)
            ->first();

        $statusPembayaran = $tagihan->pembayaran ? $tagihan->pembayaran->status_pembayaran : null;
        // dd($statusPembayaran);


        $aktivitas = AktivitasMahasiswa::with('anggota_aktivitas', 'jenis_aktivitas_mahasiswa', 'bimbing_mahasiswa')
            ->whereHas('anggota_aktivitas', function($q) use($user) {
                $q->where('id_registrasi_mahasiswa', $user->fk_id);
            })
            ->whereHas('bimbing_mahasiswa', function($q) {
                $q->where('approved', '1');
            })
            ->whereIn('id_jenis_aktivitas', ['2', '3', '4', '22'])
            ->where('id_semester', $id_semester)
            ->first();

        if (!$aktivitas) {
            return view('mahasiswa.bimbingan.tugas-akhir.index', [
                'aktivitas' => null,
                'data' => collect(),
                'dosen_pembimbing' => collect(),
                'showAlert' => true, // Flag untuk menampilkan SweetAlert
                'statusPembayaran' => $statusPembayaran,
            ]);
        }

        $data = AsistensiAkhir::where('id_aktivitas', $aktivitas->id_aktivitas)->orderBy('tanggal', 'ASC')->get();
        $dosen_pembimbing = $aktivitas->load(['bimbing_mahasiswa']);

        return view('mahasiswa.bimbingan.tugas-akhir.index', [
            'data' => $data,
            'aktivitas' => $aktivitas,
            'dosen_pembimbing' => $dosen_pembimbing,
            'showAlert' => false, // Flag untuk tidak menampilkan SweetAlert
            'statusPembayaran' => $statusPembayaran,
        ]);
    }

    public function store(AktivitasMahasiswa $aktivitas, Request $request)
    {
        $data = $request->validate([
            'tanggal' => 'required',
            'uraian' => 'required',
            'dosen_pembimbing' => 'required|exists:biodata_dosens,id_dosen',
        ]);

        $data['id_aktivitas'] = $aktivitas->id_aktivitas;
        $data['approved'] = 0;
        $data['id_dosen'] = $request->dosen_pembimbing;
        $data['tanggal'] = date('Y-m-d', strtotime($data['tanggal']));

        AsistensiAkhir::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
