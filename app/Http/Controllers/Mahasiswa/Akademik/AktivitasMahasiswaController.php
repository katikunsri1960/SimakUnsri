<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;


class AktivitasMahasiswaController extends Controller
{
    public function getAktivitas(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
        ->where('id_registrasi_mahasiswa', $id_reg)
        ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
        ->first();

        $prodi_id = $riwayat_pendidikan->id_prodi;
        
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->whereNotIn('id_status_mahasiswa', ['N'])
                    ->orderBy('id_semester', 'DESC')
                    ->first();
        
        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                    ->first();
                    
        $krs_akt = AnggotaAktivitasMahasiswa::select(
            'anggota_aktivitas_mahasiswas.*', 'aktivitas_mahasiswas.*', 'bimbing_mahasiswas.*'
        )
            ->leftJoin('aktivitas_mahasiswas', 'aktivitas_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->leftJoin('bimbing_mahasiswas', 'bimbing_mahasiswas.id_aktivitas', '=', 'anggota_aktivitas_mahasiswas.id_aktivitas')
            ->where('anggota_aktivitas_mahasiswas.id_registrasi_mahasiswa', $id_reg)
            ->where('aktivitas_mahasiswas.id_semester', $semester_aktif->id_semester)
            ->where('aktivitas_mahasiswas.id_prodi', $prodi_id)
            ->whereIn('aktivitas_mahasiswas.id_jenis_aktivitas', ['2', '3', '4', '22'])
            ->whereNotNull('bimbing_mahasiswas.id_bimbing_mahasiswa')
            ->get();

        // dd($krs_akt);

        return response()->json($krs_akt);
    }

    public function ambilAktivitas($id_matkul)
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
        ->where('id_registrasi_mahasiswa', $id_reg)
        ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
        ->first();

        $aktivitas_mk=MataKuliah::where('id_matkul', $id_matkul)->first();
        // dd($aktivitas_mk);

        $prodi_id = $riwayat_pendidikan->id_prodi;
        
        $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->whereNotIn('id_status_mahasiswa', ['N'])
                    ->orderBy('id_semester', 'DESC')
                    ->first();
        
        $semester_aktif = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                    ->first();

        $ips = AktivitasKuliahMahasiswa::select('ips')
        ->where('id_registrasi_mahasiswa', $id_reg)
        ->where('id_semester', $semester_aktif->id_semester)
        // ->where('id_status_mahasiswa', ['O'])
        ->orderBy('id_semester', 'DESC')
        ->pluck('ips')->first();

            if ($ips !== null) {
                if($ips >= 3.00){
                    $sks_max = 24;
                }elseif($ips >= 2.50 && $ips <= 2.99){
                    $sks_max = 21;
                }elseif($ips >= 2.00 && $ips <= 2.49){
                    $sks_max = 18;
                }elseif($ips >= 1.50 && $ips <= 1.99){
                    $sks_max = 15;
                }elseif($ips < 1.50){
                    $sks_max = 12;
                }else{
                    $sks_max = "Tidak Diisi";
                }
            } else {
                $sks_max = "Tidak Diisi";
            }

            $dosen_pembimbing = BiodataDosen::select('biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen', 'biodata_dosens.nidn')
                    // ->leftJoin()
                    // ->where('id_prodi', $prodi_id)
                    ->get();

        return view('mahasiswa.perkuliahan.krs.krs-regular.aktivitas-mahasiswa.ambil-aktivitas-mahasiswa', 
        [
            'id_matkul' => $id_matkul, 
            'akm'=>$akm, 
            'sks_max'=>$sks_max,
            'aktivitas_mk'=>$aktivitas_mk,
            'dosen_bimbing_aktivitas'=>$dosen_pembimbing

        ]);
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');

       
        $tahun_ajaran = SemesterAktif::leftJoin('semesters','semesters.id_semester','semester_aktifs.id_semester')
                        ->first();
        
        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran-1)
                                ->orderby('nama_dosen', 'asc');
        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%")
                  ->where('id_tahun_ajaran', $tahun_ajaran->id_tahun_ajaran);
        }

        $data = $query->get();
        // dd($data);

        return response()->json($data);
    }
    

    public function simpanAktivitas(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|max:100', // tambahkan validasi untuk Keterangan
            'lokasi' => 'required|string',
            'dosen_bimbing_aktivitas.*' => 'required',
            'id_matkul' => 'required',
        ]);
        
        try {
            // Gunakan transaksi untuk memastikan semua operasi database berhasil
            DB::transaction(function () use ($request) {
            $id_reg = auth()->user()->fk_id;

            $riwayat_pendidikan = RiwayatPendidikan::with('prodi')            
                            ->where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

            $semester_aktif = SemesterAktif::with('semester')->first();
            
            $now = Carbon::now();

            $mk_konversi = MataKuliah::where('id_matkul', $request->id_matkul)->where('id_prodi', $riwayat_pendidikan->id_prodi)->first();
            // dd($mk_konversi);

            $id_aktivitas = Uuid::uuid4()->toString();

            if($mk_konversi->nama_mata_kuliah == 'PROPOSAL SKRIPSI'){
                $id_jenis_aktivitas = 1;
                $nama_jenis_aktivitas = 'Laporan akhir studi';
            }elseif($mk_konversi->nama_mata_kuliah == 'TUGAS AKHIR' ){
                $id_jenis_aktivitas = 2;
                $nama_jenis_aktivitas = 'Tugas akhir';
            }elseif($mk_konversi->nama_mata_kuliah == 'TESIS' ){
                $id_jenis_aktivitas = 3;
                $nama_jenis_aktivitas = 'Tesis';
            }elseif($mk_konversi->nama_mata_kuliah == 'UJIAN DISERTASI' ){
                $id_jenis_aktivitas = 4;
                $nama_jenis_aktivitas = 'Disertasi';
            }elseif($mk_konversi->nama_mata_kuliah == 'KULIAH KERJA NYATA (KKN)' || $mk_konversi->nama_mata_kuliah == 'KULIAH KERJA NYATA'){
                $id_jenis_aktivitas = 5;
                $nama_jenis_aktivitas = 'Kuliah kerja nyata';
            }elseif($mk_konversi->nama_mata_kuliah == 'KERJA PRAKTEK' || $mk_konversi->nama_mata_kuliah == 'KERJA PRAKTIK' ){
                $id_jenis_aktivitas = 6;
                $nama_jenis_aktivitas = 'Kerja praktek/PKL';
            }elseif($mk_konversi->nama_mata_kuliah == 'DISERTASI DAN PUBLIKASI'){
                $id_jenis_aktivitas = 15;
                $nama_jenis_aktivitas = 'Penelitian/Riset';
            }elseif($mk_konversi->nama_mata_kuliah == 'SKRIPSI'){
                $id_jenis_aktivitas = 22;
                $nama_jenis_aktivitas = 'Skripsi';
            }else{
                return response()->json('error', 'Jenis Aktivitas Tidak Ditemukan');
            }
            
                // Simpan data ke tabel aktivitas_mahasiswas
                $aktivitas=AktivitasMahasiswa::create([
                    'feeder'=>0,
                    'id_aktivitas' => $id_aktivitas,
                    'judul' => $request->judul_skripsi,
                    'program_mbkm'=>0,
                    'nama_program_mbkm'=>'Mandiri',//tanyakan dirapat
                    'jenis_anggota'=>0,
                    'nama_jenis_anggota'=>'Personal',//tanyakan dirapat
                    'id_jenis_aktivitas'=>$id_jenis_aktivitas,
                    'nama_jenis_aktivitas'=>$nama_jenis_aktivitas,
                    'id_prodi' => $riwayat_pendidikan->id_prodi,
                    'nama_prodi'=>$riwayat_pendidikan->nama_program_studi,
                    'id_semester' => $semester_aktif->id_semester,
                    'nama_semester'=>$semester_aktif->semester->nama_semester,
                    'judul' => $request->judul,
                    'keterangan'=>$request->keterangan,
                    'lokasi'=>$request->lokasi,
                    'sk_tugas'=>Null,
                    'sumber_data'=>1,
                    'tanggal_sk_tugas'=>Null,
                    'tanggal_mulai'=>$now,//tambahkan kondisi jika aktivitas melanjutkan semester
                    'tanggal_selesai'=>Null,
                    'untuk_kampus_merdeka'=>1,
                    'asal_data'=>9,
                    'nm_asaldata'=>'',
                    'status_sync'=>'belum sync',
                    'mk_konversi'=>$request->id_matkul,
                    // tambahkan field lain yang diperlukan
                ]);

                $id_anggota = Uuid::uuid4()->toString();

                // Simpan data ke tabel anggota_aktivitas_mahasiswas
                AnggotaAktivitasMahasiswa::create([
                    'feeder'=>0,
                    'id_anggota'=>$id_anggota,
                    'id_aktivitas' => $aktivitas->id_aktivitas,
                    'judul' => $aktivitas->judul,
                    'id_registrasi_mahasiswa'=>$id_reg,
                    'nim'=>$riwayat_pendidikan->nim,
                    'nama_mahasiswa'=> $riwayat_pendidikan->nama_mahasiswa,
                    'jenis_peran'=>2,
                    'nama_jenis_peran'=>'Anggota',
                    'status_sync'=>'belum sync',
                ]);   
                
                // Periksa jumlah dosen pembimbing yang dipilih
                if (count((array)$request->dosen_bimbing_aktivitas) == 0) {
                    return redirect()->back()->with('error', 'Harap pilih minimal satu dosen pembimbing.');
                }

                $jumlah_dosen=count($request->dosen_bimbing_aktivitas);
               
                $prodi = $riwayat_pendidikan->prodi;

                for($i=0;$i<$jumlah_dosen;$i++){
                    //Generate id aktivitas mengajar
                    $id_bimbing_mahasiswa = Uuid::uuid4()->toString();
                    
                    // TAMBAHKAN TUGAS AKHIR D3
                    if($prodi->nama_jenjang_pendidikan == 'S1'){
                        if($i==0){
                            $id_kategori_kegiatan=110403;
                            $nama_kategori_kegiatan='Skripsi (pembimbing utama)';
                        }else{
                            $id_kategori_kegiatan=110407;
                            $nama_kategori_kegiatan='Skripsi (pembimbing pendamping)';
                        }
                    }elseif($prodi->nama_jenjang_pendidikan == 'S2'){
                        if($i==0){
                            $id_kategori_kegiatan=110402;
                            $nama_kategori_kegiatan='Tesis (pembimbing utama)';
                        }else{
                            $id_kategori_kegiatan=110406;
                            $nama_kategori_kegiatan='Tesis (pembimbing pendamping)';
                        }
                    }elseif($prodi->nama_jenjang_pendidikan == 'S3'){
                        if($i==0){
                            $id_kategori_kegiatan=110401;
                            $nama_kategori_kegiatan='Disertasi (pembimbing utama)';
                        }else{
                            $id_kategori_kegiatan=110405;
                            $nama_kategori_kegiatan='Disertasi (pembimbing pendamping)';
                        }
                    }
                    $dosen_pembimbing=BiodataDosen::where('id_dosen', $request->dosen_bimbing_aktivitas[$i])->first();
                    // dd($dosen_pembimbing);

                    $bimbing=BimbingMahasiswa::create([
                        'feeder'=>0,
                        'approved'=>0,
                        'id_bimbing_mahasiswa'=> $id_bimbing_mahasiswa,
                        'id_aktivitas'=>$aktivitas->id_aktivitas,
                        'judul'=>$aktivitas->judul,
                        'id_kategori_kegiatan'=>$id_kategori_kegiatan,
                        'nama_kategori_kegiatan'=>$nama_kategori_kegiatan,
                        'id_dosen'=>$dosen_pembimbing->id_dosen, 
                        'nidn'=>$dosen_pembimbing->nidn,
                        'nama_dosen'=>$dosen_pembimbing->nama_dosen,
                        'pembimbing_ke'=>$i+1,
                        'status_sync'=>'belum sync',
                    ]);
                    
                }
                // $bimbing_urut=$bimbing->orderBy('pembimbing_ke', 'ASC')->get();
                // dd($bimbing);
                
            });

            // Jika berhasil, kembalikan respons sukses
            return redirect()->route('mahasiswa.krs.index')->with('success', 'Data aktivitas mahasiswa berhasil disimpan');

        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    
    public function hapusAktivitas($id)
    {
        $id_aktivitas=AktivitasMahasiswa::where('id', $id)->pluck('id_aktivitas');
        
        DB::beginTransaction();

        try {
            // Menghapus bimbingan mahasiswa
           $bimbing = BimbingMahasiswa::where('id_aktivitas', $id_aktivitas);
            if ($bimbing) {
                $bimbing->delete();
            }
            
            // Menghapus anggota aktivitas mahasiswa
            $anggota = AnggotaAktivitasMahasiswa::where('id_aktivitas', $id_aktivitas);
            if ($anggota) {
                $anggota->delete();
            }
            
            // Menghapus aktivitas mahasiswa
            $aktivitas = AktivitasMahasiswa::findOrFail($id);
            $aktivitas->delete();

            DB::commit();

            return redirect()->route('mahasiswa.krs.index')->with('success', 'Aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('mahasiswa.krs.index')->with('error', $e->getMessage());
        }
    }

}
