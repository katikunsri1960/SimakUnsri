<?php

namespace App\Http\Controllers\Dosen\Penilaian;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\UjiMahasiswa;
use App\Models\Perkuliahan\NotulensiSidangMahasiswa;
use App\Models\Perkuliahan\RevisiSidangMahasiswa;
use App\Models\Perkuliahan\NilaiSidangMahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenilaianSidangController extends Controller
{
    public function index()
    {
        $db = new AktivitasMahasiswa;
        $data = $db->uji_dosen(auth()->user()->fk_id);
        // dd($data);
        return view('dosen.penilaian.penilaian-sidang.index', [
            'data' => $data
        ]);
    }

    public function approve_penguji(AktivitasMahasiswa $aktivitas)
    {
        // dd($aktivitas);
        $id_dosen = auth()->user()->fk_id;
        $aktivitas->uji_mahasiswa()->where('id_dosen', $id_dosen)->update([
            'status_uji_mahasiswa' => 2
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function pembatalan_penguji(AktivitasMahasiswa $aktivitas)
    {
        // dd($request->alasan_pembatalan);
        $id_dosen = auth()->user()->fk_id;
        $aktivitas->uji_mahasiswa()->where('id_dosen', $id_dosen)->update([
            'status_uji_mahasiswa' => 3
        ]);

        return redirect()->back()->with('success', 'Data berhasil dibatalkan');
    }

    public function detail_sidang($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa', 'konversi', 'uji_mahasiswa'])->where('id', $aktivitas)->first();
        $data_pelaksanaan_sidang = AktivitasMahasiswa::with(['revisi_sidang', 'notulensi_sidang', 'penilaian_sidang', 'revisi_sidang.dosen', 'penilaian_sidang.dosen'])->where('id', $aktivitas)->first();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        // dd($penguji);
        return view('dosen.penilaian.penilaian-sidang.detail-sidang.detail', [
            'data' => $data,
            'data_pelaksanaan' => $data_pelaksanaan_sidang,
            'penguji' => $penguji
        ]);
    }

    public function notulensi_sidang($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa'])
                                    ->where('id', $aktivitas)->first();
        $count_notulensi = NotulensiSidangMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->count();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        // dd($penguji);
        return view('dosen.penilaian.penilaian-sidang.detail-sidang.notulensi-sidang', [
            'data' => $data,
            'penguji' => $penguji,
            'count_notulensi' => $count_notulensi
        ]);
    }

    public function notulensi_sidang_store(Request $request, $aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::where('id', $aktivitas)->first();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        $tahun_aktif = date('Y');
        $detik = '00';

        $validate = $request->validate([
            'lokasi' => 'required',
            'tanggal_sidang' => 'required',
            'bulan_sidang' => 'required',
            'jam_mulai' => 'required',
            'menit_mulai' => 'required',
            'jam_selesai' => 'required',
            'menit_selesai' => 'required',
            'jam_mulai_presentasi' => 'required',
            'menit_mulai_presentasi' => 'required',
            'jam_selesai_presentasi' => 'required',
            'menit_selesai_presentasi' => 'required',
            'notulensi.*' => 'required'
        ]);
        //Generate tanggal pelaksanaan
        $tanggal_sidang = $tahun_aktif."-".$request->bulan_sidang."-".$request->tanggal_sidang;

        //Generate jam pelaksanaan
        $jam_mulai_sidang = $request->jam_mulai.":".$request->menit_mulai.":".$detik;
        $jam_selesai_sidang = $request->jam_selesai.":".$request->menit_selesai.":".$detik;
        $jam_mulai_presentasi = $request->jam_mulai_presentasi.":".$request->menit_mulai_presentasi.":".$detik;
        $jam_selesai_presentasi = $request->jam_selesai_presentasi.":".$request->menit_selesai_presentasi.":".$detik;

        $jumlah_notulensi = count($request->notulensi);

        try {
            DB::beginTransaction();

            if($penguji->id_kategori_kegiatan == '110501'){
                for($i=0;$i<$jumlah_notulensi;$i++){
                    NotulensiSidangMahasiswa::create(['id_aktivitas' => $data->id_aktivitas, 'id_dosen' => $penguji->id_dosen, 'lokasi' => $request->lokasi, 'tanggal_sidang' => $tanggal_sidang, 'jam_mulai_sidang' => $jam_mulai_sidang, 'jam_selesai_sidang' => $jam_selesai_sidang, 'jam_mulai_presentasi' => $jam_mulai_presentasi, 'jam_selesai_presentasi' => $jam_selesai_presentasi, 'uraian' => $request->notulensi[$i]]);
                }   
            }else{
                return redirect()->back()->with('error', 'Notulensi Hanya Bisa di Isi Oleh Ketua Penguji.');
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ' . $th->getMessage());
        }
    }

    public function revisi_sidang($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa'])
                                    ->where('id', $aktivitas)->first();
        $count_revisi = RevisiSidangMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->count();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        // dd($penguji);
        return view('dosen.penilaian.penilaian-sidang.detail-sidang.revisi-sidang', [
            'data' => $data,
            'penguji' => $penguji,
            'count_revisi' => $count_revisi
        ]);
    }

    public function revisi_sidang_store(Request $request, $aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::where('id', $aktivitas)->first();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        $tahun_aktif = date('Y');

        $validate = $request->validate([
            'tanggal_batas_perbaikan' => 'required',
            'bulan_batas_perbaikan' => 'required',
            'revisi.*' => 'required'
        ]);

        //Generate tanggal batas perbaikan
        $tanggal_batas_perbaikan = $tahun_aktif."-".$request->bulan_batas_perbaikan."-".$request->tanggal_batas_perbaikan;


        $jumlah_revisi = count($request->revisi);

        try {
            DB::beginTransaction();

            for($i=0;$i<$jumlah_revisi;$i++){
                RevisiSidangMahasiswa::create(['id_aktivitas' => $data->id_aktivitas, 'id_dosen' => $penguji->id_dosen, 'tanggal_batas_revisi' => $tanggal_batas_perbaikan, 'uraian' => $request->revisi[$i]]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ' . $th->getMessage());
        }
    }
    public function penilaian_sidang($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa'])
                                    ->where('id', $aktivitas)->first();
        $bobot_kualitas_skripsi = round((15/30)*100,2);
        $bobot_presentasi_diskusi = round((10/30)*100,2);
        $bobot_performansi = round((5/30)*100,2);
        // dd($penguji);
        return view('dosen.penilaian.penilaian-sidang.detail-sidang.penilaian-sidang', [
            'data' => $data,
            'bobot_kualitas_skripsi' => $bobot_kualitas_skripsi,
            'bobot_presentasi_diskusi' => $bobot_presentasi_diskusi,
            'bobot_performansi' => $bobot_performansi
        ]);
    }

    public function penilaian_sidang_store(Request $request, $aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::where('id', $aktivitas)->first();
        $penguji = UjiMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        $nilai_sidang = NilaiSidangMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen',$id_dosen)->first();

        $validate = $request->validate([
            'kualitas_skripsi' => 'required',
            'presentasi' => 'required',
            'performansi' => 'required'
        ]);

        //Generate tanggal penilaian
        $tanggal_penilaian = date('Y-m-d');

        //Generate nilai akhir sidang
        $bobot_kualitas_skripsi = round((15/30),2);
        $bobot_presentasi_diskusi = round((10/30),2);
        $bobot_performansi = round((5/30),2);

        $nilai_akhir_sidang = ($request->kualitas_skripsi * $bobot_kualitas_skripsi) + ($request->presentasi * $bobot_presentasi_diskusi)+ ($request->performansi * $bobot_performansi);

        try {
            DB::beginTransaction();
            
            if(!$nilai_sidang){
                NilaiSidangMahasiswa::create(['approved_prodi' => 0, 'id_aktivitas' => $data->id_aktivitas, 'id_dosen' => $penguji->id_dosen, 'id_kategori_kegiatan' => $penguji->id_kategori_kegiatan,'nilai_kualitas_skripsi' => $request->kualitas_skripsi, 'nilai_presentasi_dan_diskusi' => $request->presentasi, 'nilai_performansi' => $request->performansi, 'nilai_akhir_dosen' => $nilai_akhir_sidang, 'tanggal_penilaian_sidang' => $tanggal_penilaian]);
            }else{
                if($nilai_sidang->approved_prodi == 0){
                    NilaiSidangMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen',$id_dosen)->update(['nilai_kualitas_skripsi' => $request->kualitas_skripsi, 'nilai_presentasi_dan_diskusi' => $request->presentasi, 'nilai_performansi' => $request->performansi, 'nilai_akhir_dosen' => $nilai_akhir_sidang, 'tanggal_penilaian_sidang' => $tanggal_penilaian]);
                }else{
                    return redirect()->back()->with('error', 'Data nilai sudah disetujui prodi.');
                }  
            }

            DB::commit();

            return redirect()->route('dosen.penilaian.sidang-mahasiswa.detail-sidang', $aktivitas)->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ' . $th->getMessage());
        }
    }
}
