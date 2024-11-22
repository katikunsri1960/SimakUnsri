<?php

namespace App\Http\Controllers\Prodi\Akademik;

use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\MatkulKurikulum;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Referensi\KategoriKegiatan;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class NilaiTransferController extends Controller
{
    public function index()
    {
        $semesterAktif = SemesterAktif::first();
        $semester = $semesterAktif->id_semester;
        $db = new RiwayatPendidikan();
        $data = $db->nilai_transfer_pendidikan(auth()->user()->fk_id, $semester );
        // dd($data);
        return view('prodi.data-akademik.nilai-transfer.index', ['data' => $data]);
    } 

    public function nilai_transfer($id_reg)
    {
        $semesterAktif = SemesterAktif::first();
        $kategori = KategoriKegiatan::where('id_kategori_kegiatan', '110300')->get();
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'bimbing_mahasiswa'])->where('id_aktivitas', $aktivitas)->where('id_semester', $semesterAktif->id_semester)->first();
        $nilai_konversi = KonversiAktivitas::where('id_aktivitas', $aktivitas)->get();
        // dd($nilai_konversi);

        return view('prodi.data-akademik.nilai-transfer.input', [
            'd' => $data,
            'pengisian_nilai' => $semesterAktif,
            'kategori' => $kategori,
            'konversi' => $nilai_konversi
        ]);
    } 

    public function store_nilai_transfer($id_reg, Request $request)
    {
        $semester = SemesterAktif::with('semester')->first();

        $aktivitas_mahasiswa = AktivitasMahasiswa::with(['anggota_aktivitas_personal'])->where('id_aktivitas', $aktivitas)->where('id_semester', $semester->id_semester)->first();

        $nilai_konversi = KonversiAktivitas::with(['matkul'])->where('id_aktivitas', $aktivitas)->get();

        if(strtotime(date('Y-m-d')) < strtotime($semester->mulai_isi_nilai)){
            return redirect()->back()->with('error', 'Masa Pengisian Nilai Belum di Mulai.');
        }

        if(strtotime(date('Y-m-d')) > strtotime($semester->batas_isi_nilai)){
            return redirect()->back()->with('error', 'Masa Pengisian Nilai Telah Berakhir.');
        }

        // dd($nilai_konversi);

        $data = $request->validate([
                    'mata_kuliah.*' => 'required',
                    'nilai_angka.*' => 'required'
        ]);

        //Count jumlah dosen pengajar kelas kuliah
        $jumlah_matkul=count($request->mata_kuliah);

        $jumlah_sks_matkul = 0;

        for($j=0;$j<$jumlah_matkul;$j++){

            $matkul = MataKuliah::where('id_matkul', $request->mata_kuliah[$j])->first();
            // dd($matkul);

            $jumlah_sks_matkul = $jumlah_sks_matkul + $matkul->sks_mata_kuliah;
        }

        if ($jumlah_sks_matkul == 0) {
            return redirect()->back()->with('error', 'SKS Mata Kuliah tidak boleh 0.');
        }

        if(!$nilai_konversi){

            if ($jumlah_sks_matkul > $aktivitas_mahasiswa->sks_aktivitas) {
                return redirect()->back()->with('error', 'SKS Mata Kuliah Melebihi Batas SKS Konversi Aktivitas.');
            }

        }else{

            $jumlah_sks_nilai_konversi = $nilai_konversi->sum('sks_mata_kuliah');
            $total_sks = $jumlah_sks_matkul + $jumlah_sks_nilai_konversi;

            if ($total_sks > $aktivitas_mahasiswa->sks_aktivitas) {
                return redirect()->back()->with('error', 'SKS Mata Kuliah Melebihi Batas SKS Konversi Aktivitas.');
            }
        }

        try {
            DB::beginTransaction();

            for($i=0;$i<$jumlah_matkul;$i++){
                //Generate id aktivitas mengajar
                $id_konversi_aktivitas = Uuid::uuid4()->toString();

                $matkul = MataKuliah::where('id_matkul', $request->mata_kuliah[$i])->first();

                $jumlah_sks = $matkul->sks_mata_kuliah;

                if($request->nilai_angka[$i] > 100){
                    $nilai_akhir_sidang = 100;
                }
        
                if($request->nilai_angka[$i] >= 86 && $request->nilai_angka[$i] <=100){
                    $nilai_indeks = '4.00';
                    $nilai_huruf = 'A';
                }
                else if($request->nilai_angka[$i] >= 71 && $request->nilai_angka[$i] < 86){
                    $nilai_indeks = '3.00';
                    $nilai_huruf = 'B';
                }
                else if($request->nilai_angka[$i] >= 56 && $request->nilai_angka[$i] < 71){
                    $nilai_indeks = '2.00';
                    $nilai_huruf = 'C';
                }
                else if($request->nilai_angka[$i] >= 41 && $request->nilai_angka[$i] < 56){
                    $nilai_indeks = '1.00';
                    $nilai_huruf = 'D';
                }
                else if($request->nilai_angka[$i] >= 0 && $request->nilai_angka[$i] < 41){
                    $nilai_indeks = '0.00';
                    $nilai_huruf = 'E';
                }else{
                    return redirect()->back()->with('error', 'Nilai di luar range skala nilai.');
                }

                //Store data to table tanpa substansi kuliah
                KonversiAktivitas::create(['feeder'=> 0, 'id_konversi_aktivitas'=> $id_konversi_aktivitas, 'id_matkul'=> $matkul->id_matkul, 'nama_mata_kuliah' => $matkul->nama_mata_kuliah, 'id_aktivitas' => $aktivitas_mahasiswa->id_aktivitas, 'judul' => $aktivitas_mahasiswa->judul, 'id_anggota' => $aktivitas_mahasiswa->anggota_aktivitas_personal->id_anggota, 'nama_mahasiswa' => $aktivitas_mahasiswa->anggota_aktivitas_personal->nama_mahasiswa, 'nim'=> $aktivitas_mahasiswa->anggota_aktivitas_personal->nim, 'sks_mata_kuliah' => $matkul->sks_mata_kuliah, 'nilai_angka' => $request->nilai_angka[$i], 'nilai_indeks' => $nilai_indeks, 'nilai_huruf' => $nilai_huruf, 'id_semester' => $semester->id_semester, 'nama_semester' => $semester->semester->nama_semester, 'status_sync' => 'belum sync']);

            }

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
        }
    }

    public function delete_nilai_transfer($konversi)
    {
        // dd($id_matkul);
        $nilai_konversi = KonversiAktivitas::where('id_konversi_aktivitas', $konversi)->first();

        if($nilai_konversi->feeder == 1){
            return redirect()->back()->with('error', 'Data Nilai tidak bisa dihapus karena sudah di sinkronisasi');
        }

        try {
            DB::beginTransaction();

            KonversiAktivitas::where('id_konversi_aktivitas', $konversi)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Data Kelas Berhasil di Hapus!!');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Kelas Gagal di Hapus. '. $th->getMessage());
        }
    }
}
