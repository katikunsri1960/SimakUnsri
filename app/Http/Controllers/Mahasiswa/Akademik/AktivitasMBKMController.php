<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Mahasiswa\AktivitasMagang;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;

class AktivitasMBKMController extends Controller
{
    public function view()
    {
        // $id_reg = auth()->user()->fk_id;

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.index');
    }

    public function index_non_pertukaran()
    {
        $id_reg = auth()->user()->fk_id;
        
        // $data = AktivitasMagang::where('id_registrasi_mahasiswa', $id_reg)->get();
        // $anggota_aktivitas = AnggotaAktivitasMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->get();

        $semester_aktif = SemesterAktif::first();
        
        $data = AktivitasMahasiswa::with(['anggota_aktivitas', 'bimbing_mahasiswa'])
                ->whereHas('anggota_aktivitas' , function($query) use ($id_reg) {
                    $query->where('id_registrasi_mahasiswa', $id_reg);
                })
                ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20'])
                ->get();
                // dd($data);

        $today = Carbon::now()->toDateString();

        $deadline = Carbon::parse($semester_aktif->krs_selesai)->toDateString();

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.non-pertukaran.index', ['data' => $data, 'semester_aktif' => $semester_aktif,'today'=>$today ,'deadline'=>$deadline ]);
    }

    public function tambah()
    {
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

        $aktivitas_mbkm = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                        ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20'])
                        ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                        ->get();
                        // dd($aktivitas_mbkm);

        $dosen_pembimbing = BiodataDosen::select('biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen', 'biodata_dosens.nidn')
                    // ->leftJoin()
                    // ->where('id_prodi', $prodi_id)
                    ->first();

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.non-pertukaran.store', ['data' => $data, 'dosen_bimbing_aktivitas'=>$dosen_pembimbing, 'aktivitas_mbkm'=>$aktivitas_mbkm]);
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');
        // $prodi_id = auth()->user()->fk_id;
        $tahun_ajaran = SemesterAktif::with('semester')->first();

        // $tahun_ajaran = Semester::where('id_semester','=','20231')->where('a_periode_aktif','=','1')->get();

        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1)
                                ->orderby('nama_dosen', 'asc')->limit(10);

        if ($search) {
            $query->where('nama_dosen', 'like', "%{$search}%")
                  ->orWhere('nama_program_studi', 'like', "%{$search}%")
                  ->where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1);
        }

        $data = $query->get();
        // dd($data);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'aktivitas_mbkm' => 'required',
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|max:100', 
            'lokasi' => 'required|string|max:255',
            'dosen_bimbing_aktivitas' => 'required',
        ]);

        $id_reg = auth()->user()->fk_id;
        $aktivitas_mbkm = $request->aktivitas_mbkm;

        // Pengecekan apakah aktivitas MBKM yang sama sudah ada untuk mahasiswa tersebut
        // $existing_aktivitas = AktivitasMahasiswa::with([
        //                         'anggota_aktivitas' => function($query) use ($id_reg) {
        //                             $query->where('id_registrasi_mahasiswa', $id_reg);
        //                         },])
        //                         ->where('id_jenis_aktivitas', $aktivitas_mbkm)
        //                         ->get();

        //                         dd($existing_aktivitas);

        // if ($existing_aktivitas) {
        //     return redirect()->back()->with('error', 'Anda sudah mengajukan aktivitas MBKM dengan jenis aktivitas yang sama.');
        // }

        // Lanjutkan proses penyimpanan jika tidak ada aktivitas yang sama
        try {
            // Gunakan transaksi untuk memastikan semua operasi database berhasil
            DB::transaction(function () use ($request, $id_reg, $aktivitas_mbkm) {
            $id_reg = auth()->user()->fk_id;

            $riwayat_pendidikan = RiwayatPendidikan::with('prodi')            
                            ->where('id_registrasi_mahasiswa', $id_reg)
                            ->first();

            $semester_aktif = SemesterAktif::with('semester')->first();
            
            $now = Carbon::now();

            // $mk_konversi = MataKuliah::where('id_matkul', $request->id_matkul)->where('id_prodi', $riwayat_pendidikan->id_prodi)->first();
            // dd($request->aktivitas_mbkm);

            $id_aktivitas = Uuid::uuid4()->toString();

            $aktivitas_mbkm = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                            ->where('id_jenis_aktivitas', $request->aktivitas_mbkm)
                            ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                            ->first();
                            // dd($aktivitas_mbkm);

            // Simpan data ke tabel aktivitas_mahasiswas
                $aktivitas=AktivitasMahasiswa::create([
                    'feeder'=>0,
                    'id_aktivitas' => $id_aktivitas,
                    // 'judul' => $request->judul_skripsi,
                    'program_mbkm'=>0,
                    'nama_program_mbkm'=>'Mandiri',//tanyakan dirapat
                    'jenis_anggota'=>0,
                    'nama_jenis_anggota'=>'Personal',//tanyakan dirapat
                    'id_jenis_aktivitas'=>$aktivitas_mbkm->id_jenis_aktivitas,
                    'nama_jenis_aktivitas'=>$aktivitas_mbkm->nama_jenis_aktivitas,
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
                    'mk_konversi'=>'',
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

                // if()
                
                // Periksa jumlah dosen pembimbing yang dipilih
                if (count((array)$request->dosen_bimbing_aktivitas) == 0) {
                    return redirect()->back()->with('error', 'Harap pilih minimal satu dosen pembimbing.');
                }

                //Generate id aktivitas mengajar
                $id_bimbing_mahasiswa = Uuid::uuid4()->toString();
                
                $dosen_pembimbing = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)
                                ->where('id_registrasi_dosen', $request->dosen_bimbing_aktivitas)->first();

                BimbingMahasiswa::create([
                    'feeder'=>0,
                    'approved'=>0,
                    'id_bimbing_mahasiswa'=> $id_bimbing_mahasiswa,
                    'id_aktivitas'=>$aktivitas->id_aktivitas,
                    'judul'=>$aktivitas->judul,
                    'id_kategori_kegiatan'=>110300,
                    'nama_kategori_kegiatan'=>'Membimbing Kuliah Kerja Nyata, Praktek Kerja Nyata, Praktek Kerja Lapangan, termasuk membimbing pelatihan militer mahasiswa, pertukaran mahasiswa,  Magang, kuliah berbasis penelitian, wirausaha, dan bentuk lain pengabdian kepada masyarakat, dan sejenisnya',
                    'id_dosen'=>$dosen_pembimbing->id_dosen, 
                    'nidn'=>$dosen_pembimbing->nidn,
                    'nama_dosen'=>$dosen_pembimbing->nama_dosen,
                    'pembimbing_ke'=>1,
                    'status_sync'=>'belum sync',
                ]);
                    
                
                // $bimbing_urut=$bimbing->orderBy('pembimbing_ke', 'ASC')->get();
                // dd($bimbing);
                
            });

            return redirect()->route('mahasiswa.perkuliahan.mbkm.non-pertukaran')->with('success', 'Data aktivitas mahasiswa berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    //PERTUKARAN
    public function index_pertukaran()
    {
        $id_reg = auth()->user()->fk_id;
        
        // $data = AktivitasMagang::where('id_registrasi_mahasiswa', $id_reg)->get();
        // $anggota_aktivitas = AnggotaAktivitasMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->get();

        $semester_aktif = SemesterAktif::first();
        
        $data = AktivitasMahasiswa::with(['anggota_aktivitas', 'bimbing_mahasiswa'])
                ->whereHas('anggota_aktivitas' , function($query) use ($id_reg) {
                    $query->where('id_registrasi_mahasiswa', $id_reg);
                })
                ->whereIn('id_jenis_aktivitas',['21'])
                ->get();
                // dd($data);

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.pertukaran.index', ['data' => $data, 'semester_aktif' => $semester_aktif]);
    }

    public function tambah_pertukaran()
    {
        $id_reg = auth()->user()->fk_id;
        $data = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg)->first();

        $aktivitas_mbkm = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                        ->whereIn('id_jenis_aktivitas',['21'])
                        ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                        ->get();
                        // dd($aktivitas_mbkm);

        $dosen_pembimbing = BiodataDosen::select('biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen', 'biodata_dosens.nidn')
                    ->first();

        return view('mahasiswa.perkuliahan.krs.aktivitas-mbkm.pertukaran.store', ['data' => $data, 'dosen_bimbing_aktivitas'=>$dosen_pembimbing, 'aktivitas_mbkm'=>$aktivitas_mbkm]);
    }

    public function store_pertukaran(Request $request)
    {
        $validated = $request->validate([
            'aktivitas_mbkm' =>'required',
            'judul' => 'required|string|max:255',
            'keterangan' => 'nullable|max:100', // tambahkan validasi untuk Keterangan
            'lokasi' => 'required|string|max:255',
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

            // $mk_konversi = MataKuliah::where('id_matkul', $request->id_matkul)->where('id_prodi', $riwayat_pendidikan->id_prodi)->first();
            // dd($request->aktivitas_mbkm);

            $id_aktivitas = Uuid::uuid4()->toString();

            $aktivitas_mbkm = AktivitasMahasiswa::select('id_jenis_aktivitas','nama_jenis_aktivitas')
                            ->where('id_jenis_aktivitas', $request->aktivitas_mbkm)
                            ->groupBy('id_jenis_aktivitas', 'nama_jenis_aktivitas')
                            ->first();
                            // dd($aktivitas_mbkm);

            // Simpan data ke tabel aktivitas_mahasiswas
                $aktivitas=AktivitasMahasiswa::create([
                    'feeder'=>0,
                    'id_aktivitas' => $id_aktivitas,
                    'program_mbkm'=>0,
                    'nama_program_mbkm'=>'Mandiri',//tanyakan dirapat
                    'jenis_anggota'=>0,
                    'nama_jenis_anggota'=>'Personal',//tanyakan dirapat
                    'id_jenis_aktivitas'=>$aktivitas_mbkm->id_jenis_aktivitas,
                    'nama_jenis_aktivitas'=>$aktivitas_mbkm->nama_jenis_aktivitas,
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
                    'mk_konversi'=>'',
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
            });

            // Jika berhasil, kembalikan respons sukses
            return redirect()->route('mahasiswa.perkuliahan.mbkm.pertukaran')->with('success', 'Data aktivitas mahasiswa berhasil disimpan');

        } catch (\Exception $e) {
            // Jika terjadi kesalahan, kembalikan respons dengan pesan kesalahan
            return redirect()->back()->with('error', $e->getMessage());
        }
        
        return redirect()->route('mahasiswa.perkuliahan.mbkm.pertukaran')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function hapusAktivitas($id)
    {
        $id_aktivitas=AktivitasMahasiswa::where('id', $id)->pluck('id_aktivitas');
        // dd($id_aktivitas);
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

            return redirect()->route('mahasiswa.perkuliahan.mbkm.non-pertukaran')->with('success', 'Aktivitas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('mahasiswa.perkuliahan.mbkm.non-pertukaran')->with('error', $e->getMessage());
        }
    }
}
