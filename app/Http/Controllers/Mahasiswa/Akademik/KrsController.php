<?php

namespace App\Http\Controllers\Mahasiswa\Akademik;

use Carbon\Carbon;
use App\Models\Fakultas;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Dosen\BiodataDosen;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\BeasiswaMahasiswa;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Mahasiswa\PengajuanCuti;
use App\Models\Perkuliahan\KelasKuliah;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Cache\RateLimiting\Limit;
use App\Models\Perkuliahan\MatkulMerdeka;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\PrasyaratMatkul;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\RencanaPembelajaran;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;


class KrsController extends Controller
{
    public function index(Request $request)
    {
        return view('mahasiswa.perkuliahan.krs.index');
    }

    public function view(Request $request)
    {
        // DATA BAHAN
        if ($request->has('semester') && $request->semester != '') {
            $semester_select = $request->semester;
        } else {
            $semester_select = SemesterAktif::first()->id_semester;
        }
        // dd($semester_select);

        $id_reg = auth()->user()->fk_id;
        
        $riwayat_pendidikan = RiwayatPendidikan::with('pembimbing_akademik')
                    ->select('riwayat_pendidikans.*')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->first();

        $semester_aktif = SemesterAktif::first();

        $total_sks_akt = 0;
        $total_sks_regular=0;
        $total_sks_merdeka=0;

        //DATA AKTIVITAS
        $db = new MataKuliah();

        $db_akt = new AktivitasMahasiswa();

        // $data_akt = $db->getMKAktivitas($riwayat_pendidikan->id_prodi, $riwayat_pendidikan->id_kurikulum);

        list($krs_akt, $data_akt_ids, $mk_akt) = $db_akt->getKrsAkt($id_reg, $semester_aktif->id_semester);
        

        // $semester = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
        //             ->orderBy('id_semester', 'DESC')
        //             ->get();

        $semester = Semester::orderBy('id_semester', 'DESC')
                    ->whereBetween('id_semester', [$riwayat_pendidikan->id_periode_masuk, $semester_aktif->id_semester])
                    // ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                    ->get();
        // dd($semester);

        // Mengambil status mahasiswa untuk semester aktif
        $status_mahasiswa = $semester->where('id_semester', $semester_select)
                    ->pluck('id_status_mahasiswa')
                    ->first();

        // Menentukan status mahasiswa berdasarkan hasil query
        $data_status_mahasiswa = $status_mahasiswa !== null ? $status_mahasiswa : 'X';

        // $semester_urut = Semester::orderBy('id_semester', 'ASC')
        //             // ->limit(10)
        //             ->get();

        $semester_ke = Semester::orderBy('id_semester', 'ASC')
                ->whereBetween('id_semester', [$riwayat_pendidikan->id_periode_masuk, $semester_aktif->id_semester])
                ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
                ->count();

        // $semester_ke = Semester::orderBy('id_semester', 'ASC')
        //     ->whereBetween('id_semester', [$riwayat_pendidikan->id_periode_masuk, $semester_aktif->id_semester])
        //     // ->whereRaw('RIGHT(id_semester, 1) != ?', [3])
        //     ->get();

        // Menghitung jumlah semester, mengabaikan semester pendek
        // $semester_ke = $semester->filter(function($item) {
        //     return substr($item->id_semester, -1) != '3';
        // })->count();
        // dd($semester);

        $sks_max = $db->getSksMax($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_periode_masuk);

        $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $semester_select, $data_akt_ids);

        $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_select);

    // DATA MK_MERDEKA
        $fakultas=Fakultas::all();

        // $selectedFakultasId = $request->input('fakultas_id');

        // $prodi = ProgramStudi::where('fakultas_id', $selectedFakultasId)->get();

        // $mk_merdeka = $db->getMKMerdeka($prodi, $semester_select);
        // dd($mk_merdeka);

        // MATAKULIAH TANPA GANJIL GENAP
        $mk_regular = $db->getMKRegular($riwayat_pendidikan, $data_akt_ids, $semester_select);
        // dd($sks_max);

    // TAGIHAN PEMBAYARAN
        $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->first();
        // dd($beasiswa);

        // if (!empty($existingCuti)) {
        //     if ($existingCuti->approved == 0) {
        //         return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sedang diproses. Tunggu persetujuan atau batalkan pengajuan sebelum membuat pengajuan baru.');
        //     }
        //     elseif ($existingCuti->approved == 1) {
        //         return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sudah disetujui.');
        //     }
        // }

        $tagihan = DB::connection('keu_con')
            ->table('tagihan')
            ->leftJoin('pembayaran', 'tagihan.id_record_tagihan', '=', 'pembayaran.id_record_tagihan')
            ->where('tagihan.nomor_pembayaran', $riwayat_pendidikan->nim)
            ->where('tagihan.kode_periode', $semester_aktif->id_semester)
            ->select(
                'tagihan.nama',
                'tagihan.nomor_pembayaran',
                'tagihan.total_nilai_tagihan',
                'tagihan.kode_periode',
                // 'tagihan.nama_periode',
                'tagihan.waktu_berlaku',
                'tagihan.waktu_berakhir',
                'pembayaran.status_pembayaran'
            )
            ->first();
            // dd($tagihan);

            $cuti = PengajuanCuti::where('id_registrasi_mahasiswa', $id_reg)->where('id_semester', $semester_aktif->id_semester)->first();
            // dd($cuti);

            $transkrip = TranskripMahasiswa::select(
                DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                // DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ips'), 
                DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
                )
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->whereNotIn('nilai_huruf', ['F', ''])
                // ->groupBy('id_registrasi_mahasiswa')
                ->first();
                // dd($transkrip);

    // TOTAL SELURUH SKS
        $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
        $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
        $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

        $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt;

        return view('mahasiswa.perkuliahan.krs.krs-regular.index',[
            'formatDosenPengajar' => function($dosenPengajar) {
                return $this->formatDosenPengajar($dosenPengajar);
            }], compact(
            'mk_regular', 'semester_select',
            'riwayat_pendidikan',
            'semester_aktif',
            'krs_regular',
            'krs_merdeka',
            'total_sks_merdeka',
            'total_sks_regular',
            // 'akm', 
            'sks_max', 
            'semester',
            'total_sks',
            'status_mahasiswa',
            'data_status_mahasiswa',
            'semester_ke',
            'fakultas',
            'krs_akt',
            'mk_akt',
            'total_sks_akt',
            'beasiswa',
            'tagihan', 
            'cuti',
            'transkrip'
        ));
    }

    public function generateAKM(Request $request)
    {
        $id_reg = auth()->user()->fk_id;
        $semester_aktif = SemesterAktif::first();
        $db = new MataKuliah();
        $db_akt = new AktivitasMahasiswa();

        $status_bayar=0;
        $status_cuti=1;

        $akm_aktif= AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->where('id_semester', $semester_aktif->id_semester)
                    ->get();
                    // dd($akm_aktif);
        
        $idKelasKuliah = $request->input('id_kelas_kuliah');
            // dd($idKelasKuliah);

            $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
                    ->first();

            list($krs_akt, $data_akt_ids) = $db_akt->getKrsAkt($id_reg, $semester_aktif);

            $sks_max = $db->getSksMax($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_periode_masuk);

            $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $semester_aktif, $data_akt_ids);

            $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_aktif);

            $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
            $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
            $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

            $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt;

            $sks_mk = KelasKuliah::select('sks_mata_kuliah')
                    ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'kelas_kuliahs.id_matkul')
                    ->where('id_kelas_kuliah', $idKelasKuliah)
                    ->pluck('sks_mata_kuliah')
                    ->first();

            // Check if the total SKS exceeds the maximum allowed SKS
            if (($total_sks + $sks_mk) > $sks_max) {
                return response()->json(['message' => 'Total SKS tidak boleh melebihi SKS maksimum.', 'sks_max' => $sks_max], 400);
            }

            $kelas_mk = KelasKuliah::leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul','=','kelas_kuliahs.id_matkul')
                    ->where('id_kelas_kuliah', $idKelasKuliah)->first();

            $transkrip = TranskripMahasiswa::select(
                            DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                            DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
                        )
                        ->where('id_registrasi_mahasiswa', $id_reg)
                        ->whereNotIn('nilai_huruf', ['F', ''])
                        ->groupBy('id_registrasi_mahasiswa')
                        ->first();
                        // dd($transkrip);
        if($status_bayar==1 && $akm_aktif->isEmpty()  && $status_cuti==0)
        {
            $peserta = AktivitasKuliahMahasiswa::create([
                'id_registrasi_mahasiswa' => $id_reg,
                'nim' => $riwayat_pendidikan->nim,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'id_prodi' => $riwayat_pendidikan->id_prodi,
                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                'id_semester'=> $semester_aktif->id_semester,
                'nama_semester'=> $semester_aktif->semester->nama_semester,
                'id_status_mahasiswa' => 'A',
                'nama_status_mahasiswa' => 'Aktif',
                'ips'=> 0,
                'ipk'=> $transkrip->ipk,
                'sks_semester'=> $total_sks,
                'sks_total'=>$transkrip->total_sks,
                'biaya_kuliah_smt' => 0,
                'id_pembiayaan' => NULL,
                'status_sync' => 'belum sync',
            ]);
        }
        elseif($status_bayar==1 && $akm_aktif != NULL  && $status_cuti==0)
        {
            $updated = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)->where('id_semester', $semester_aktif->id_semester)->update([
                'nim' => $riwayat_pendidikan->nim,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'id_prodi' => $riwayat_pendidikan->id_prodi,
                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                'id_semester'=> $semester_aktif->id_semester,
                'nama_semester'=> $semester_aktif->semester->nama_semester,
                'id_status_mahasiswa' => 'A',
                'nama_status_mahasiswa' => 'Aktif',
                'ips' => 0,
                'ipk' => $transkrip->ipk,
                'sks_semester' => $total_sks,
                'sks_total' => $transkrip->total_sks,
                'biaya_kuliah_smt' => 0,
                'id_pembiayaan' => NULL,
                'status_sync' => 'belum sync',
            ]);
        }
        elseif($status_bayar==0 && $akm_aktif->isEmpty() && $status_cuti==1)
        {
            $peserta = AktivitasKuliahMahasiswa::create([
                'id_registrasi_mahasiswa' => $id_reg,
                'nim' => $riwayat_pendidikan->nim,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'id_prodi' => $riwayat_pendidikan->id_prodi,
                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                'id_semester'=> $semester_aktif->id_semester,
                'nama_semester'=> $semester_aktif->semester->nama_semester,
                'id_status_mahasiswa' => 'C',
                'nama_status_mahasiswa' => 'Cuti',
                'ips'=> 0,
                'ipk'=> $transkrip->ipk,
                'sks_semester'=>$total_sks,
                'sks_total'=>$transkrip->total_sks,
                'biaya_kuliah_smt' => 0,
                'id_pembiayaan' => NULL,
                'status_sync' => 'belum sync',
            ]);
        }
        elseif($status_bayar==0 && $akm_aktif->isEmpty() && $status_cuti==0)
        {
            $peserta = AktivitasKuliahMahasiswa::create([
                'id_registrasi_mahasiswa' => $id_reg,
                'nim' => $riwayat_pendidikan->nim,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'id_prodi' => $riwayat_pendidikan->id_prodi,
                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
                'id_periode_masuk'=> $riwayat_pendidikan->periode_masuk->id_semester,
                'id_semester'=> $semester_aktif->id_semester,
                'nama_semester'=> $semester_aktif->semester->nama_semester,
                'id_status_mahasiswa' => 'N',
                'nama_status_mahasiswa' => 'Non-Aktif',
                'ips'=> 0,
                'ipk'=> $transkrip->ipk,
                'sks_semester'=>$total_sks,
                'sks_total'=>$transkrip->total_sks,
                'biaya_kuliah_smt' => 0,
                'id_pembiayaan' => NULL,
                'status_sync' => 'belum sync',
            ]);
        }

        // return view('mahasiswa.krs.index', compact(
        //     'akm_aktif'
        // ));
    }

    public function pilih_prodi(Request $request)
    {
        $fakultasId = $request->input('id');
        $id_semester = $request->input('semester');
        $id_prodi = $request->input('id_prodi');

        $prodi = ProgramStudi::where('fakultas_id', $fakultasId)->get();

        return response()->json(['prodi' => $prodi]);
    }


    public function pilihMataKuliahMerdeka(Request $request)
    {
        $id_reg = auth()->user()->fk_id;

        $semester_aktif = SemesterAktif::first()->id_semester;
        // Ambil id_prodi dari request

        $id_prodi = $request->input('id_prodi');

        // $selectedFakultasId = $request->input('fakultas_id');

        // $prodi = ProgramStudi::where('fakultas_id', $selectedFakultasId)->get();


        $db = new MataKuliah();

        // Query untuk mengambil data mata kuliah merdeka berdasarkan id_prodi yang dipilih
        $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_aktif);

        $mkMerdeka = $db->getMKMerdeka($semester_aktif, $id_prodi);
        // dd($semester_aktif);

        return response()->json(['mk_merdeka' => $mkMerdeka, 'krs_merdeka'=>$krs_merdeka]);
    }



    public function get_kelas_kuliah(Request $request)
    {
        $idMatkul = $request->get('id_matkul');


        $semester_aktif = SemesterAktif::pluck('id_semester');

        $kelasKuliah = KelasKuliah::with(['dosen_pengajar','dosen_pengajar.dosen'])
                    ->whereHas('dosen_pengajar' , function($query) {
                            $query->whereNotNull('id_dosen');
                        })
                    ->withCount('peserta_kelas')
                    ->where('id_semester',  $semester_aktif)
                    ->where('id_matkul', $idMatkul)
                    ->orderBy('nama_kelas_kuliah')
                    ->get();
        
        foreach ($kelasKuliah as $kelas) {
            $kelas->is_kelas_ambil = $this->cekApakahKelasSudahDiambil($request->user()->id, $kelas->id_matkul);
        }
        // dd($kelasKuliah);

        return response()->json($kelasKuliah);
    }



    private function cekApakahKelasSudahDiambil($id_registrasi_mahasiswa, $id_matkul)
    {
        $kelasDiambil = PesertaKelasKuliah::where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
            ->where('id_matkul', $id_matkul)
            ->exists();

        return $kelasDiambil;
    }



    public function ambilKelasKuliah(Request $request)
    {
        try {
            $idKelasKuliah = $request->input('id_kelas_kuliah');
            // dd($idKelasKuliah);

            $id_reg = auth()->user()->fk_id;

            $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
                    ->first();

            $semester_aktif = SemesterAktif::first();

            $db = new MataKuliah();

            $db_akt = new AktivitasMahasiswa();

            list($krs_akt, $data_akt_ids) = $db_akt->getKrsAkt($id_reg, $semester_aktif);


            $sks_max = $db->getSksMax($id_reg, $semester_aktif->id_semester, $riwayat_pendidikan->id_periode_masuk);

            $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $semester_aktif, $data_akt_ids);

            $krs_merdeka = $db->getKrsMerdeka($id_reg, $semester_aktif);

            $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
            $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
            $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

            $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt;

            $sks_mk = KelasKuliah::select('sks_mata_kuliah')
                    ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', '=', 'kelas_kuliahs.id_matkul')
                    ->where('id_kelas_kuliah', $idKelasKuliah)
                    ->pluck('sks_mata_kuliah')
                    ->first();

            

            // Check if the total SKS exceeds the maximum allowed SKS
            if (($total_sks + $sks_mk) > $sks_max) {
                return response()->json(['message' => 'Total SKS tidak boleh melebihi SKS maksimum.', 'sks_max' => $sks_max], 400);
            }

            $kelas_mk = KelasKuliah::leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul','=','kelas_kuliahs.id_matkul')
                    ->where('id_kelas_kuliah', $idKelasKuliah)->first();

            //QUERY RPS
            $rps=RencanaPembelajaran::where('id_matkul', $kelas_mk->id_matkul)->get();
            
    
            if ($rps->count() == 0) {
                // return redirect()->back()->with('error', 'Rencana Pembelajaran Semester tidak ditemukan untuk mata kuliah ini.');
                return response()->json(['message' => 'Rencana Pembelajaran Semester tidak ditemukan untuk mata kuliah ini.'], 400);
            }

            DB::beginTransaction();

            $peserta = PesertaKelasKuliah::create([
                'approved' => 0,
                'id_kelas_kuliah' => $idKelasKuliah,
                'id_registrasi_mahasiswa' => $id_reg,
                'nim' => $riwayat_pendidikan->nim,
                'id_mahasiswa' => $riwayat_pendidikan->id_mahasiswa,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                'id_prodi' => $riwayat_pendidikan->id_prodi,
                'nama_kelas_kuliah' => $kelas_mk->nama_kelas_kuliah,
                'id_matkul' => $kelas_mk->id_matkul,
                'kode_mata_kuliah' => $kelas_mk->kode_mata_kuliah,
                'nama_mata_kuliah' => $kelas_mk->nama_mata_kuliah,
                'angkatan' => $riwayat_pendidikan->periode_masuk->id_tahun_ajaran,
            ]);

            DB::commit();

            return response()->json(['message' => 'Data berhasil disimpan', 'sks_max' => $sks_max, 'sks_mk'=>$sks_mk, '$peserta'=>$peserta], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan data'], 500);
            // return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function hapus_kelas_kuliah(PesertaKelasKuliah $pesertaKelas)
    {
        // $peserta= PesertaKelasKuliah::where('id', $pesertaKelas)->first();
        // dd($pesertaKelas);

        if ($pesertaKelas->approved ==1) {
            // return redirect()->back()->with('error', 'Rencana Pembelajaran Semester tidak ditemukan untuk mata kuliah ini.');
            // return response()->json(['message' => 'Anda tidak dapat menghapus Mata Kuliah ini.'], 400);
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus Mata Kuliah ini, Mata Kuliah telah disetujui Dosen Pembimbing Akademik');
        }

        $pesertaKelas->delete();

        return redirect()->back()->with('success', 'Mata Kuliah Berhasil di Hapus');
    }

    public function cekPrasyarat(Request $request)
    {
        $idMatkul = $request->get('id_matkul');
        $id_reg = $request->get('id_reg');

        // Dapatkan mata kuliah prasyarat
        $prasyarat = PrasyaratMatkul::where('id_matkul', $idMatkul)->pluck('id_matkul_prasyarat');

        // Jika tidak ada prasyarat, langsung return true
        if ($prasyarat->isEmpty()) {
            return response()->json(['prasyarat_dipenuhi' => true]);
        }

        // Cek apakah mahasiswa sudah mengambil mata kuliah prasyarat
        $mataKuliahDipenuhi = PesertaKelasKuliah::where('id_registrasi_mahasiswa', $id_reg)
                ->whereIn('id_matkul', $prasyarat)
                ->where('approved', '1')
                ->exists();

        if ($mataKuliahDipenuhi) {
            return response()->json(['prasyarat_dipenuhi' => true]);
        } else {
            // Dapatkan nama mata kuliah prasyarat yang belum diambil
            $mataKuliahSyarat = MataKuliah::whereIn('id_matkul', $prasyarat)
                ->pluck('nama_mata_kuliah')
                ->toArray();

            // Gabungkan nama mata kuliah prasyarat dengan koma
            $mataKuliahSyaratString = implode(', ', $mataKuliahSyarat);
            // dd($mataKuliahSyaratString);

            return response()->json([
                'prasyarat_dipenuhi' => false,
                'mata_kuliah_syarat' => $mataKuliahSyaratString
            ]);
        }
    }

    public function krs_print(Request $request, $id_semester)
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('pembimbing_akademik')
                ->where('id_registrasi_mahasiswa', $id_reg)
                ->first();

        $prodi = ProgramStudi::with(['fakultas', 'jurusan'])
                ->where('id_prodi', $riwayat_pendidikan->id_prodi)->first();

        $fakultas_pdf = (str_replace("Fakultas ","",$prodi->fakultas->nama_fakultas));
        // dd($fakultas_pdf);

        $semester_aktif = SemesterAktif::first();

        $today = Carbon::now();
        $deadline = Carbon::parse($semester_aktif->krs_selesai);

        $db = new MataKuliah();

        $data_akt = $db->getMKAktivitas($riwayat_pendidikan->id_prodi, $riwayat_pendidikan->id_kurikulum);

        if(isEmpty($data_akt))
        {
            $mk_akt=NULL;
            $data_akt_ids = NULL;

        }
        else
        {
            $mk_akt = $data_akt;
            $data_akt_ids = array_column($mk_akt, 'id_matkul');
        }

        if ($request->has('semester') && $request->semester != '') {
            $semester_select = $request->semester;
        } else {
            $semester_select = SemesterAktif::first()->id_semester;
        }

        $data = PesertaKelasKuliah::with('kelas_kuliah')
                    ->whereHas('kelas_kuliah', function($query) use ($id_semester) {
                        $query ->where('id_semester', $id_semester);
                    })
                    ->where('id_registrasi_mahasiswa', $id_reg)
                    ->get();
        // dd($prodi);

        $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $id_semester, $data_akt_ids);

        $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

        $nama_mhs = $riwayat_pendidikan->nama_mahasiswa;
        $nim = $riwayat_pendidikan->nim;
        $nama_smt = Semester::where('id_semester', $id_semester)->first()->nama_semester;
        $dosen_pa = BiodataDosen::where('id_dosen', $riwayat_pendidikan->dosen_pa)->first();
        // dd($request->semester);
        if (empty($dosen_pa)) {
            return response()->json(['error' => 'Dosen PA tidak ditemukan.']);
        }


        //DATA AKTIVITAS
        $db = new MataKuliah();

        $db_akt = new AktivitasMahasiswa();

        $data_akt = $db->getMKAktivitas($riwayat_pendidikan->id_prodi, $riwayat_pendidikan->id_kurikulum);

        list($krs_akt, $data_akt_ids) = $db_akt->getKrsAkt($id_reg, $id_semester);

        $semester = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $id_reg)
                    ->orderBy('id_semester', 'DESC')
                    ->get();

        // Mengambil status mahasiswa untuk semester aktif
        $status_mahasiswa = $semester->where('id_semester', $id_semester)
                    ->pluck('id_status_mahasiswa')
                    ->first();

        // Menentukan status mahasiswa berdasarkan hasil query
        $data_status_mahasiswa = $status_mahasiswa !== null ? $status_mahasiswa : 'X';

        // Menghitung jumlah semester, mengabaikan semester pendek
        $semester_ke = $semester->filter(function($item) {
            return substr($item->id_semester, -1) != '3';
        })->count();

        $krs_regular = $db->getKrsRegular($id_reg, $riwayat_pendidikan, $id_semester, $data_akt_ids);

        $krs_merdeka = $db->getKrsMerdeka($id_reg, $id_semester);


    // TOTAL SELURUH SKS
        $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
        $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
        $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');

        $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt;

        $pdf = PDF::loadview('mahasiswa.perkuliahan.krs.krs-regular.pdf', [
            'today'=> $today,
            'deadline'=> $deadline,
            'data' => $data,
            'nim' => $nim,
            'nama_mhs' => $nama_mhs,
            'dosen_pa' => $dosen_pa,
            'prodi' => $prodi,
            'fakultas_pdf' => $fakultas_pdf,
            'nama_smt' => $nama_smt,
            'semester_aktif' => $semester_aktif,
            'id_semester' => $id_semester,
            'total_sks_regular' => $total_sks_regular,
            'krs_regular'=> $krs_regular,
            'data_status_mahasiswa' => $data_status_mahasiswa,
            'krs_regular' => $krs_regular,
            'krs_merdeka' => $krs_merdeka,
            'krs_akt' => $krs_akt,
            'total_sks_akt' => $total_sks_akt,
            'total_sks_merdeka' => $total_sks_merdeka,
            'total_sks_regular' => $total_sks_regular,
            'total_sks' => $total_sks,

        ])->setPaper('a4', 'portrait');

        return $pdf->stream('KRS_' . $nim . '_' . $nama_smt . '.pdf');
    }

    public function checkDosenPA($id_semester)
    {
        $id_reg = auth()->user()->fk_id;

        $riwayat_pendidikan = RiwayatPendidikan::with('pembimbing_akademik')
            ->where('id_registrasi_mahasiswa', $id_reg)
            ->first();

        $dosen_pa = BiodataDosen::where('id_dosen', $riwayat_pendidikan->dosen_pa)->first();

        if (empty($dosen_pa)) {
            return response()->json(['error' => 'Dosen PA belum ditentukan, Silahkan Hubungi Koor. Program Studi.']);
        }

        return response()->json(['success' => true]);
    }

}
