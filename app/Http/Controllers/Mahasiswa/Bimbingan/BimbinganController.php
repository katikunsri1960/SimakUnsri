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
        
        if ($request->has('semester') && $request->semester != '') {
            $id_semester = $request->semester;
        } else {
            $id_semester = SemesterAktif::first()->id_semester;
        }

        // Query untuk mendapatkan data
        $data = AktivitasMahasiswa::with('anggota_aktivitas', 'jenis_aktivitas_mahasiswa', 'bimbing_mahasiswa', 'uji_mahasiswa')
                    ->whereHas('anggota_aktivitas', function($q) use($user) {
                        $q->where('id_registrasi_mahasiswa', $user->fk_id);
                    })
                    ->whereHas('bimbing_mahasiswa', function($q) {
                        $q->where('approved', '1');
                    })
                    ->whereIn('id_jenis_aktivitas', ['1','2', '3', '4', '22'])
                    ->orderBy('id_jenis_aktivitas', 'ASC')
                    ->where('id_semester', $id_semester)
                    ->get();

        $semester = Semester::orderBy('id_semester', 'desc')->get();

        // PENGECEKAN STATUS PEMBAYARAN
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $user->fk_id)->count();

        $tagihan = Tagihan::with('pembayaran')
            ->whereIn('tagihan.nomor_pembayaran', [$id_test, $nim])
            ->where('kode_periode', $id_semester)
            ->first();
        
        if ($tagihan){
            if($tagihan->pembayaran->status_pembayaran == 1) {
                $statusPembayaran = $tagihan->pembayaran->status_pembayaran;
            }
            elseif($tagihan->pembayaran->status_pembayaran == 0) {
                $statusPembayaran = NULL;
            }
        }else{
            $statusPembayaran = NULL;
        }

        // Pengecekan apakah $data kosong atau tidak
        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Data Aktivitas Mahasiswa tidak ditemukan.');
        }      

        // Jika belum ada pembayaran dan tidak ada beasiswa
        if ($statusPembayaran == NULL && $beasiswa > 0) {
            return redirect()->back()->with('error', 'Anda belum menyelesaikan pembayaran untuk semester ini!');
        }

        // foreach ($data as $d){
        //     foreach($d->bimbing_mahasiswa){
        //         if ( $bimbingan->approved == 0) {
        //             return redirect()->back()->with('error', 'Dosen pembimbing Anda belum disetujui oleh Koordinator Program Studi!');
        //         };
        //     };
        // };
        
        // dd($tagihan);


        return view('mahasiswa.bimbingan.tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester,
            'id_semester' => $id_semester,
            'statusPembayaran'=> $statusPembayaran,
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
