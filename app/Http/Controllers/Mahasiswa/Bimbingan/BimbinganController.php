<?php

namespace App\Http\Controllers\Mahasiswa\Bimbingan;

use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\AsistensiAkhir;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;

class BimbinganController extends Controller
{
    public function bimbingan_tugas_akhir(Request $request)
    {
        $user = auth()->user();
        $nim = $user->username;
        $id_test = Registrasi::where('rm_nim', $user->username)->pluck('rm_no_test')->first();
        $semester_aktif = SemesterAktif::first();
        $riwayat_pendidikan = RiwayatPendidikan::with('pembimbing_akademik')
                    ->select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $user->fk_id)
                    ->first();
        
        $semester = Semester::orderBy('id_semester', 'DESC')
                    ->whereBetween('id_semester', [$riwayat_pendidikan->id_periode_masuk, $semester_aktif->id_semester])
                    // ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                    ->get();

        if ($request->has('semester') && $request->semester != '') {
            $semester_select = $request->semester;
        } else {
            $semester_select = SemesterAktif::first()->id_semester;
        }

        // Query untuk mendapatkan data
        $data = AktivitasMahasiswa::with('anggota_aktivitas', 'jenis_aktivitas_mahasiswa', 'bimbing_mahasiswa', 'uji_mahasiswa')
                    ->whereHas('anggota_aktivitas', function($q) use($riwayat_pendidikan) {
                        $q->where('id_registrasi_mahasiswa', $riwayat_pendidikan->id_registrasi_mahasiswa);
                    })
                    ->whereHas('bimbing_mahasiswa', function($q) {
                        $q->where('approved', '1');
                    })
                    ->whereIn('id_jenis_aktivitas', ['1','2', '3', '4', '22'])
                    ->orderBy('id_jenis_aktivitas', 'ASC')
                    ->where('id_semester', $semester_select)
                    ->get();

        // PENGECEKAN STATUS PEMBAYARAN
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)->count();

        $tagihan = Tagihan::with('pembayaran')
            ->whereIn('tagihan.nomor_pembayaran', [$id_test, $nim])
            ->where('kode_periode', $semester_select)
            ->first();
        
            if($tagihan){
                if($tagihan->pembayaran){
                    $pembayaran = $tagihan->pembayaran;
                }
                else{
                    $pembayaran = NULL;
                }
            }else{
                $pembayaran = NULL;
            }
        // $pembayaran = 0;

        // dd($pembayaran, $data, $beasiswa);

        // Pengecekan apakah $data kosong atau tidak
        if ($data->isEmpty()) {
            return redirect()->back()->withErrors('Data Aktivitas Mahasiswa tidak ditemukan, Silahkan ambil aktivitas mahasiswa di menu KRS!');
        }
        // if ($data->isEmpty()) {
        //     session()->flash('error', 'Data Aktivitas Mahasiswa tidak ditemukan.');
        // }

        // Jika belum ada pembayaran dan tidak ada beasiswa
        if ($pembayaran == NULL && $beasiswa == 0) {
            session()->flash('error', 'Anda belum menyelesaikan pembayaran untuk semester ini!');
        }
        // if ($pembayaran == 0) {
        //     return redirect()->back()->withErrors('Anda belum menyelesaikan pembayaran untuk semester ini!');
        // }

        // dd($tagihan);
        return view('mahasiswa.bimbingan.tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester,
            'id_semester' => $semester_select,
            'pembayaran'=> $pembayaran,
            'beasiswa'=>$beasiswa
        ]);
    }
    
    public function asistensi(AktivitasMahasiswa $aktivitas)
    {
        $user = auth()->user();
        // dd($aktivitas);

        $data = AsistensiAkhir::where('id_aktivitas', $aktivitas->id_aktivitas)->get();

        $aktivitas = $aktivitas->load(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi', 'konversi', 'uji_mahasiswa']);
        $data_pelaksanaan_sidang = $aktivitas->load(['revisi_sidang', 'notulensi_sidang', 'penilaian_sidang', 'revisi_sidang.dosen', 'penilaian_sidang.dosen']);

        $pembimbing_ke = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
                            ->first()->pembimbing_ke;
                    
        $dosen_pembimbing = $aktivitas->load(['bimbing_mahasiswa']);
        // dd($dosen_pembimbing);
        return view('mahasiswa.bimbingan.tugas-akhir.asistensi', [
            'data' => $data,
            'data_pelaksanaan' => $data_pelaksanaan_sidang,
            'dosen_pembimbing' => $dosen_pembimbing,
            'aktivitas' => $aktivitas,
            'pembimbing_ke' => $pembimbing_ke,
        ]);
    }

    public function asistensi_store(AktivitasMahasiswa $aktivitas, Request $request)
    {
        $data = $request->validate([
                    'tanggal' => 'required',
                    'uraian' => 'required',
                ]);

        $data['id_aktivitas'] = $aktivitas->id_aktivitas;
        $data['approved'] = 0;
        $data['id_dosen'] = $request->dosen_pembimbing;
        $data['tanggal'] = date('Y-m-d', strtotime($data['tanggal']));
        
        AsistensiAkhir::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }
}
