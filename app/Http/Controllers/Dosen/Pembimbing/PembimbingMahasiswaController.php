<?php

namespace App\Http\Controllers\Dosen\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\AsistensiAkhir;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Mahasiswa\BiodataMahasiswa;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\UjiMahasiswa;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\NilaiSidangMahasiswa;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use App\Models\Perkuliahan\NilaiPerkuliahan;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Perkuliahan\KonversiAktivitas;
use App\Models\Perkuliahan\Konversi;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Perpus\BebasPustaka;
use App\Models\Connection\Usept;
use App\Models\Connection\CourseUsept;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PembimbingMahasiswaController extends Controller
{
    public function bimbingan_akademik()
    {
        $semester = SemesterAktif::with(['semester'])->first();

        $data = RiwayatPendidikan::with(['prodi', 'peserta_kelas', 'aktivitas_mahasiswa'])
                    ->withCount(['peserta_kelas' => function($query) use ($semester) {
                        $query->whereHas('kelas_kuliah', function($query) use ($semester) {
                            $query->where('id_semester', $semester->id_semester)
                                ->where('approved', 0);
                        });
                    }, 'aktivitas_mahasiswa' => function($query) use ($semester) {
                        $query->where('id_semester', $semester->id_semester)
                            ->where('approve_krs', 0);
                    }])
                    ->where(function($query) use ($semester) {
                        $query->whereHas('peserta_kelas', function($query) use ($semester) {
                            $query->whereHas('kelas_kuliah', function($query) use ($semester) {
                                $query->where('id_semester', $semester->id_semester);
                            });
                        })
                        ->orWhereHas('aktivitas_mahasiswa', function($query) use ($semester) {
                            $query->where('id_semester', $semester->id_semester);
                        });
                    })
                    ->where('dosen_pa', auth()->user()->fk_id)
                ->get();
        // $dataAktivitas = AktivitasMahasiswa::where('')

        return view('dosen.pembimbing.akademik.index', [
            'data' => $data,
            'semester' => $semester,
        ]);
    }

    public function bimbingan_akademik_detail(RiwayatPendidikan $riwayat)
    {
        $id = $riwayat->id_registrasi_mahasiswa;
        $biodata = BiodataMahasiswa::where('id_mahasiswa', $riwayat->id_mahasiswa)->first();
        $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $riwayat->id_kurikulum)->first();
        $semester = SemesterAktif::first();

        // dd($nilai_usept_mhs);

        try {
            // Set the time limit to 30 seconds (adjust as needed)
            set_time_limit(30);

            $nilai_usept_mhs = Usept::whereIn('nim', [$riwayat->nim, $biodata->nik])->max('score');
            $db_course_usept = new CourseUsept;
            $nilai_course = $db_course_usept->whereIn('nim', [$riwayat->nim, $biodata->nik])->get();

            if($nilai_usept_mhs !== null) {
                $nilai_usept_final = $nilai_usept_mhs;
    
                if($nilai_course){
                    foreach($nilai_course as $n){
                        $nilai_hasil_course = $db_course_usept->KonversiNilaiUsept($n->grade, $n->total_score);
                        
                        if($nilai_hasil_course > $nilai_usept_mhs){
                            $nilai_usept_final = $nilai_hasil_course;
                            // Hentikan loop karena syarat sudah terpenuhi
                            break;
                        }
                    }
                }
            }

        } catch (\Throwable $th) {
            //throw $th;
            $nilai_usept_final = "Belum Ada Nilai";
            \Log::error($th->getMessage());
        }

        $data = PesertaKelasKuliah::with(['kelas_kuliah.matkul'])
                ->whereHas('kelas_kuliah', function($query) use ($semester) {
                    $query->where('id_semester', $semester->id_semester);
                })
                ->where('id_registrasi_mahasiswa', $id)
                // ->withSum('kelas_kuliah.matkul as total_sks', 'sks_mata_kuliah')
                ->orderBy('kode_mata_kuliah')
                ->get();


        $aktivitas = AktivitasMahasiswa::with('anggota_aktivitas_personal', 'konversi')
                    ->whereHas('anggota_aktivitas_personal', function($query) use ($id) {
                        $query->where('id_registrasi_mahasiswa', $id);
                    })
                    ->where('id_semester', $semester->id_semester)
                    ->whereIn('id_jenis_aktivitas', [1,2,3,4,5,6,22])
                    ->get(); 
        
        $aktivitas_mbkm = AktivitasMahasiswa::with('anggota_aktivitas_personal', 'konversi')
                        ->whereHas('anggota_aktivitas_personal', function($query) use ($id) {
                            $query->where('id_registrasi_mahasiswa', $id);
                        })
                        ->where('id_semester', $semester->id_semester)
                        ->whereIn('id_jenis_aktivitas',[13,14,15,16,17,18,19,20,21])
                        ->get();

        return view('dosen.pembimbing.akademik.detail', [
            'riwayat' => $riwayat,
            'nilai_usept' => $nilai_usept_final,
            'data' => $data,
            'aktivitas' => $aktivitas,
            'aktivitas_mbkm' => $aktivitas_mbkm,
            'semester_aktif' => $semester
        ]);
    }

    public function bimbingan_akademik_approve_all(RiwayatPendidikan $riwayat)
    {
        $id = $riwayat->id_registrasi_mahasiswa;

        $db = new PesertaKelasKuliah();

        $store = $db->approve_all($id);
        // dd($store);
        return redirect()->back()->with($store['status'], $store['message']);
    }

    public function bimbingan_akademik_batal_approve(RiwayatPendidikan $riwayat)
    {
        $id = $riwayat->id_registrasi_mahasiswa;

        $db = new PesertaKelasKuliah();

        $req = $db->batal_approve($id);

        return redirect()->back()->with($req['status'], $req['message']);
    }

    public function bimbingan_non_akademik()
    {
        return view('dosen.pembimbing.bimbingan-non-akademik');
    }

    public function bimbingan_tugas_akhir(Request $request)
    {
        if ($request->has('semester') && $request->semester != '') {
            $id_semester = $request->semester;
        } else {
            $id_semester = SemesterAktif::first()->id_semester;
        }

        $db = new AktivitasMahasiswa();
        $data = $db->bimbing_ta(auth()->user()->fk_id, $id_semester);

        $semester = Semester::orderBy('id_semester', 'desc')->get();
        // dd($data);
        return view('dosen.pembimbing.tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester,
            'id_semester' => $id_semester,
        ]);
    } 

    public function approve_pembimbing(AktivitasMahasiswa $aktivitas)
    {
        // dd($aktivitas);
        $id_dosen = auth()->user()->fk_id;
        $aktivitas->bimbing_mahasiswa()->where('id_dosen', $id_dosen)->update([
            'approved_dosen' => 1,
            'alasan_pembatalan' => NULL
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function pembatalan_pembimbing(Request $request, $aktivitas)
    {
        // dd($request->alasan_pembatalan);
        $id_dosen = auth()->user()->fk_id;
        BimbingMahasiswa::where('id_aktivitas',$aktivitas)->where('id_dosen', $id_dosen)->update([
            'approved_dosen' => 2,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Data berhasil dibatalkan');
    }

    public function asistensi(AktivitasMahasiswa $aktivitas)
    {
        $data = AsistensiAkhir::where('id_aktivitas', $aktivitas->id_aktivitas)->get();

        $aktivitas = $aktivitas->load(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa', 'prodi', 'konversi', 'uji_mahasiswa']);
        $data_pelaksanaan_sidang = $aktivitas->load(['revisi_sidang', 'notulensi_sidang', 'penilaian_sidang', 'revisi_sidang.dosen', 'penilaian_sidang.dosen']);

        $repository = BebasPustaka::where('id_registrasi_mahasiswa', $aktivitas->anggota_aktivitas_personal->id_registrasi_mahasiswa)->first();

        if (!$aktivitas->konversi) {
            $penilaian_langsung = ['penilaian_langsung' => 0];
        }else{
            $penilaian_langsung = Konversi::where('id_kurikulum', $aktivitas->anggota_aktivitas_personal->mahasiswa->id_kurikulum)->where('id_matkul', $aktivitas->konversi->id_matkul)->first();
        }      

        $pembimbing_ke = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
                            ->where('id_dosen', auth()->user()->fk_id)
                            ->first()->pembimbing_ke;
        // dd($penilaian_langsung);
        return view('dosen.pembimbing.tugas-akhir.asistensi', [
            'data' => $data,
            'data_pelaksanaan' => $data_pelaksanaan_sidang,
            'aktivitas' => $aktivitas,
            'repository' => $repository,
            'penilaian_langsung' => $penilaian_langsung,
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
        $data['approved'] = 1;
        $data['id_dosen'] = auth()->user()->fk_id;
        $data['tanggal'] = date('Y-m-d', strtotime($data['tanggal']));

        AsistensiAkhir::create($data);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function asistensi_approve(AsistensiAkhir $asistensi)
    {
        $dosen = auth()->user()->fk_id;

        $pembimbing_ke = BimbingMahasiswa::where('id_aktivitas', $asistensi->id_aktivitas)
                            ->where('id_dosen', $dosen)
                            ->first()->pembimbing_ke;

        if ($asistensi->id_dosen != $dosen && $pembimbing_ke != 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda tidak memiliki akses untuk menyetujui asistensi ini'
            ]);
        }

        $store = $asistensi->update([
            'approved' => 1
        ]);

        if ($store) {
            return response()->json([
                'status' => 'success',
                'message' => 'Asistensi berhasil disetujui!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyetujui asistensi'
            ]);
        }
    }

    public function ajuan_sidang(AktivitasMahasiswa $aktivitas)
    {
        $data = $aktivitas->load(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa', 'prodi']);

        // dd($data);
        return view('dosen.pembimbing.tugas-akhir.pengajuan-sidang', [
            'data' => $data
        ]);
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');

        // Log the search term for debugging
        // \Log::info('Search term: ' . $search);

        // Get the active semester and associated year
        $tahun_ajaran = SemesterAktif::with('semester')->first();

        // Start building the query
        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran)
                                ->orderBy('nama_dosen', 'asc');

        // Add search conditions if search term is present
        if ($search) {
            $query->where(function ($q) use ($search, $tahun_ajaran) {
                $q->where('nama_dosen', 'like', "%{$search}%")
                ->orWhere('nama_program_studi', 'like', "%{$search}%")
                ->where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran);
            });
        }

        // Get the results
        $data = $query->get();

        // Return the results as JSON
        return response()->json($data);
    }

    public function ajuan_sidang_store(Request $request, $aktivitas)
    {
        // dd($request->judul);
        $data = $request->validate([
            'judul' => 'required',
            'dosen_penguji' => 'nullable',
            'dosen_penguji.*' => 'required_if:dosen_penguji,!=,null',
            'penguji_ke' => 'nullable',
            'penguji_ke' => 'required_if:penguji_ke,!=,null'
        ]);

        try {
            DB::beginTransaction();

            $aktivitasMahasiswa = AktivitasMahasiswa::with('prodi')->where('id', $aktivitas)->first();
            $bimbingMahasiswa = BimbingMahasiswa::where('id_aktivitas', $aktivitasMahasiswa->id_aktivitas)->get();
            $ujiMahasiswa = UjiMahasiswa::where('id_aktivitas', $aktivitasMahasiswa->id_aktivitas)->get();
            // dd($aktivitas, $aktivitasMahasiswa);
            if (!$aktivitasMahasiswa) {
                return redirect()->back()->with('error', 'Aktivitas Mahasiswa tidak ditemukan.');
            }

            $pembimbing = $bimbingMahasiswa->where('id_dosen', auth()->user()->fk_id)->first();

            if (!$pembimbing || $pembimbing->pembimbing_ke != 1) {
                return redirect()->back()->with('error', 'Hanya pembimbing utama yang dapat mengajukan.');
            }

            $data_mahasiswa = AnggotaAktivitasMahasiswa::with(['mahasiswa', 'mahasiswa.biodata'])
                                ->where('id_aktivitas', $aktivitasMahasiswa->id_aktivitas)
                                ->first();
            
            $aktivitasMahasiswa->update(['judul' => $data['judul'], 'approve_sidang' => 1]);
            $data_mahasiswa->update(['judul' => $data['judul']]);
        
            foreach ($bimbingMahasiswa as $b) {
                $b->update(['judul' => $data['judul']]);
            }
        
            if ($ujiMahasiswa) {
                foreach ($ujiMahasiswa as $u) {
                    $u->update(['judul' => $data['judul']]);
                }
            }

            // $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $data_mahasiswa->mahasiswa->id_kurikulum)->first();
            // $nilai_usept_mhs = Usept::whereIn('nim', [$data_mahasiswa->nim, $data_mahasiswa->mahasiswa->biodata->nik])->max('score');
            // $db_course_usept = new CourseUsept;
            // $nilai_course = $db_course_usept->whereIn('nim', [$data_mahasiswa->nim, $data_mahasiswa->mahasiswa->biodata->nik])->get();

            // if (in_array($aktivitasMahasiswa->prodi->id_jenjang_pendidikan, [31, 32, 37])) 
            // {
            //     $aktivitasMahasiswa->update(['judul' => $data['judul'], 'approve_sidang' => 1]);
            //     $data_mahasiswa->update(['judul' => $data['judul']]);
            
            //     foreach ($bimbingMahasiswa as $b) {
            //         $b->update(['judul' => $data['judul']]);
            //     }
            
            //     if ($ujiMahasiswa) {
            //         foreach ($ujiMahasiswa as $u) {
            //             $u->update(['judul' => $data['judul']]);
            //         }
            //     }
            // } else {
            //     if($aktivitasMahasiswa->id_jenis_aktivitas == 2){
            //         $aktivitasMahasiswa->update(['judul' => $data['judul'], 'approve_sidang' => 1]);
            //         $data_mahasiswa->update(['judul' => $data['judul']]);
                
            //         foreach ($bimbingMahasiswa as $b) {
            //             $b->update(['judul' => $data['judul']]);
            //         }
                
            //         if ($ujiMahasiswa) {
            //             foreach ($ujiMahasiswa as $u) {
            //                 $u->update(['judul' => $data['judul']]);
            //             }
            //         }
            //     }else{
            //         if ($nilai_usept_mhs >= $nilai_usept_prodi->nilai_usept) {
            //             $aktivitasMahasiswa->update(['judul' => $data['judul'], 'approve_sidang' => 1]);
            //             $data_mahasiswa->update(['judul' => $data['judul']]);
                
            //             foreach ($bimbingMahasiswa as $b) {
            //                 $b->update(['judul' => $data['judul']]);
            //             }
                
            //             if ($ujiMahasiswa) {
            //                 foreach ($ujiMahasiswa as $u) {
            //                     $u->update(['judul' => $data['judul']]);
            //                 }
            //             }
            //         } else {
            //             if($nilai_course){
            //                 foreach($nilai_course as $n){
            //                     $nilai_hasil_course = $db_course_usept->KonversiNilaiUsept($n->grade, $n->total_score);
                                
            //                     // Jika nilai course sudah memenuhi syarat, lanjutkan
            //                     if($nilai_hasil_course >= $nilai_usept_prodi->nilai_usept){
            //                         $aktivitasMahasiswa->update(['judul' => $data['judul'], 'approve_sidang' => 1]);
            //                         $data_mahasiswa->update(['judul' => $data['judul']]);
                        
            //                         foreach ($bimbingMahasiswa as $b) {
            //                             $b->update(['judul' => $data['judul']]);
            //                         }
                        
            //                         if ($ujiMahasiswa) {
            //                             foreach ($ujiMahasiswa as $u) {
            //                                 $u->update(['judul' => $data['judul']]);
            //                             }
            //                         }
            //                         // Hentikan loop karena syarat sudah terpenuhi
            //                         break;
            //                     }
            //                 }
                        
            //                 // Cek setelah loop jika tidak ada nilai yang memenuhi syarat
            //                 if ($nilai_hasil_course < $nilai_usept_prodi->nilai_usept) {
            //                     return redirect()->back()->with('error', 'Mahasiswa belum menyelesaikan syarat kelulusan nilai USEPT.');
            //                 }

            //             } else {
            //                 return redirect()->back()->with('error', 'Mahasiswa belum memiliki data course untuk nilai USEPT.');
            //             }                        
            //         }
            //     }
            // }
            

            if (!empty($data['dosen_penguji']) && !empty($request->penguji_ke)) {
                $semester_aktif = SemesterAktif::first();

                $dosen_penguji = PenugasanDosen::where('id_tahun_ajaran', $semester_aktif->semester->id_tahun_ajaran)
                                ->whereIn('id_registrasi_dosen', $data['dosen_penguji'])
                                ->get();

                if ($dosen_penguji->count() == 0 || $dosen_penguji->count() != count($data['dosen_penguji'])) {
                    $dosen_pengajar = PenugasanDosen::where('id_tahun_ajaran', $semester_aktif->semester->id_tahun_ajaran - 1)
                                    ->whereIn('id_registrasi_dosen', $data['dosen_penguji'])
                                    ->get();
                }

                $jumlah_dosen = count($data['dosen_penguji']);

                for ($i = 0; $i < $jumlah_dosen; $i++) {
                    $id_uji_mahasiswa = Uuid::uuid4()->toString();
                    $dosen = $dosen_penguji->firstWhere('id_registrasi_dosen', $data['dosen_penguji'][$i]);

                    if (!$dosen) {
                        $dosen = $dosen_pengajar->firstWhere('id_registrasi_dosen', $data['dosen_penguji'][$i]);
                    }

                    $pembimbing = $bimbingMahasiswa->where('id_dosen', $dosen->id_dosen)->first();

                    if($pembimbing){
                        return redirect()->back()->with('error', 'Dosen Pembimbing Tidak Bisa Menjadi Penguji.');
                    }

                    $kategori_kegiatan = $request->penguji_ke[$i] == '1' ? '110501' : '110502';
                    $nama_kategori_kegiatan = $request->penguji_ke[$i] == '1' ? 'Ketua Penguji' : 'Anggota Penguji';

                    UjiMahasiswa::create([
                        'feeder' => 0,
                        'id_uji' => $id_uji_mahasiswa,
                        'id_aktivitas' => $aktivitasMahasiswa->id_aktivitas,
                        'judul' => $data['judul'],
                        'id_kategori_kegiatan' => $kategori_kegiatan,
                        'nama_kategori_kegiatan' => $nama_kategori_kegiatan,
                        'id_dosen' => $dosen->id_dosen,
                        'nidn' => $dosen->nidn,
                        'nama_dosen' => $dosen->nama_dosen,
                        'penguji_ke' => $request->penguji_ke[$i],
                        'status_uji_mahasiswa' => 0,
                        'status_sync' => 'Belum Sync'
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ' . $th->getMessage());
        }
    }

    public function bimbingan_aktivitas(Request $request)
    {
        if ($request->has('semester') && $request->semester != '') {
            $id_semester = $request->semester;
        } else {
            $id_semester = SemesterAktif::first()->id_semester;
        }

        $db = new AktivitasMahasiswa();
        $data = $db->bimbing_non_ta(auth()->user()->fk_id, $id_semester);

        $semester = Semester::orderBy('id_semester', 'desc')->get();

        foreach ($data as $d) {
            $d->penilaian_langsung = 0;
            if ($d->konversi) {
                $result = Konversi::where('id_kurikulum', $d->anggota_aktivitas_personal->mahasiswa->id_kurikulum)
                    ->where('id_matkul', $d->konversi->id_matkul)
                    ->first();

                if ($result) {
                    $d->penilaian_langsung = $result->penilaian_langsung;
                }
            }
        }      
        
        // dd($penilaian_langsung);
        return view('dosen.pembimbing.non-tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester,
            'id_semester' => $id_semester
        ]);
    } 

    public function penilaian_sidang($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa'])
                                    ->where('id', $aktivitas)->first();
        $bobot_kualitas_skripsi = round((7/15)*100,2);
        $bobot_presentasi_diskusi = round((5/15)*100,2);
        $bobot_performansi = round((3/15)*100,2);
        // dd($penguji);
        return view('dosen.pembimbing.tugas-akhir.penilaian-sidang', [
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
        $pembimbing = BimbingMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $id_dosen)->first();
        $nilai_sidang = NilaiSidangMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen',$id_dosen)->first();

        $validate = $request->validate([
            'proses_bimbingan' => 'required',
            'kualitas_skripsi' => 'required',
            'presentasi' => 'required',
            'performansi' => 'required'
        ]);

        //Generate tanggal penilaian
        $tanggal_penilaian = $data->jadwal_ujian;

        //Generate nilai akhir sidang
        $bobot_kualitas_skripsi = round((7/15),2);
        $bobot_presentasi_diskusi = round((5/15),2);
        $bobot_performansi = round((3/15),2);

        $nilai_akhir_sidang = ($request->kualitas_skripsi * $bobot_kualitas_skripsi) + ($request->presentasi * $bobot_presentasi_diskusi)+ ($request->performansi * $bobot_performansi);

        try {
            DB::beginTransaction();

            BimbingMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen', $pembimbing->id_dosen)->update(['nilai_proses_bimbingan' => $request->proses_bimbingan]);
            
            if(!$nilai_sidang){
                NilaiSidangMahasiswa::create(['approved_prodi' => 0, 'id_aktivitas' => $data->id_aktivitas, 'id_dosen' => $pembimbing->id_dosen, 'id_kategori_kegiatan' => $pembimbing->id_kategori_kegiatan,'nilai_kualitas_skripsi' => $request->kualitas_skripsi, 'nilai_presentasi_dan_diskusi' => $request->presentasi, 'nilai_performansi' => $request->performansi, 'nilai_akhir_dosen' => $nilai_akhir_sidang, 'tanggal_penilaian_sidang' => $tanggal_penilaian]);
            }else{
                if($nilai_sidang->approved_prodi == 0){
                    NilaiSidangMahasiswa::where('id_aktivitas', $data->id_aktivitas)->where('id_dosen',$id_dosen)->update(['nilai_kualitas_skripsi' => $request->kualitas_skripsi, 'nilai_presentasi_dan_diskusi' => $request->presentasi, 'nilai_performansi' => $request->performansi, 'nilai_akhir_dosen' => $nilai_akhir_sidang, 'tanggal_penilaian_sidang' => $tanggal_penilaian]);
                }else{
                    return redirect()->back()->with('error', 'Data nilai sudah disetujui prodi.');
                }  
            }
            

            DB::commit();

            return redirect()->route('dosen.pembimbing.bimbingan-tugas-akhir.asistensi', $data)->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ' . $th->getMessage());
        }
    }

    public function penilaian_langsung($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa'])
                                    ->where('id', $aktivitas)->first();

        $data_nilai = KonversiAktivitas::where('id_aktivitas', $data->id_aktivitas)->first();
        // dd($penguji);
        return view('dosen.pembimbing.tugas-akhir.penilaian-langsung', [
            'data' => $data,
            'data_nilai' => $data_nilai
        ]);
    }

    public function penilaian_langsung_aktivitas($aktivitas)
    {
        $id_dosen = auth()->user()->fk_id;
        $data = AktivitasMahasiswa::with(['anggota_aktivitas_personal', 'anggota_aktivitas_personal.mahasiswa'])
                                    ->where('id', $aktivitas)->first();

        $data_nilai = KonversiAktivitas::where('id_aktivitas', $data->id_aktivitas)->first();
        // dd($penguji);
        return view('dosen.pembimbing.non-tugas-akhir.penilaian-langsung', [
            'data' => $data,
            'data_nilai' => $data_nilai
        ]);
    }

    public function penilaian_langsung_store(Request $request, $aktivitas)
    {
        $data = AktivitasMahasiswa::with('anggota_aktivitas_personal')->where('id', $aktivitas)->first();
        $konversi_matkul = MataKuliah::where('id_matkul', $data->mk_konversi)->first();
        $bimbingMahasiswa = BimbingMahasiswa::where('id_aktivitas', $data->id_aktivitas)->get();
        $data_nilai = KonversiAktivitas::where('id_aktivitas', $data->id_aktivitas)->first();

        if(is_null($data->sk_tugas)){
            return redirect()->back()->with('error', 'SK Tugas Aktivitas Harus Di Isi.');
        }

        $pembimbing = $bimbingMahasiswa->where('id_dosen', auth()->user()->fk_id)->first();

        if (!$pembimbing || $pembimbing->pembimbing_ke != 1) {
            return redirect()->back()->with('error', 'Hanya pembimbing utama yang dapat memberikan nilai.');
        }

        $validate = $request->validate([
            'judul' => 'required',
            'nilai_langsung' => 'required'
        ]);

        $data->update(['judul' => $validate['judul']]);
        $data->anggota_aktivitas_personal->update(['judul' => $validate['judul']]);
    
        foreach ($bimbingMahasiswa as $b) {
            $b->update(['judul' => $validate['judul']]);
        }

        $nilai_langsung = $request->nilai_langsung;

        if($nilai_langsung > 100){
            $nilai_langsung = 100;
        }

        if($nilai_langsung >= 86 && $nilai_langsung <=100){
            $nilai_indeks = '4.00';
            $nilai_huruf = 'A';
        }
        else if($nilai_langsung >= 71 && $nilai_langsung < 86){
            $nilai_indeks = '3.00';
            $nilai_huruf = 'B';
        }
        else if($nilai_langsung >= 56 && $nilai_langsung < 71){
            $nilai_indeks = '2.00';
            $nilai_huruf = 'C';
        }
        else if($nilai_langsung >= 41 && $nilai_langsung < 56){
            $nilai_indeks = '1.00';
            $nilai_huruf = 'D';
        }
        else if($nilai_langsung >= 0 && $nilai_langsung < 41){
            $nilai_indeks = '1.00';
            $nilai_huruf = 'D';
        }else{
            return redirect()->back()->with('error', 'Nilai di luar range skala nilai.');
        }

        try {
            DB::beginTransaction();

            $data->update(['tanggal_selesai' => $data->jadwal_ujian]);

            $id_konversi_aktivitas = Uuid::uuid4()->toString();
            
            if(!$data_nilai){
                KonversiAktivitas::create(['feeder' => 0, 'id_konversi_aktivitas' => $id_konversi_aktivitas, 'id_matkul' => $konversi_matkul->id_matkul, 'nama_mata_kuliah' => $konversi_matkul->nama_mata_kuliah,'id_aktivitas' => $data->id_aktivitas, 'judul' => $data->judul, 'id_anggota' => $data->anggota_aktivitas_personal->id_anggota, 'nama_mahasiswa' => $data->anggota_aktivitas_personal->nama_mahasiswa, 'nim' => $data->anggota_aktivitas_personal->nim, 'sks_mata_kuliah' => $konversi_matkul->sks_mata_kuliah, 'nilai_angka' => $nilai_langsung, 'nilai_indeks' => $nilai_indeks, 'nilai_huruf' => $nilai_huruf, 'id_semester' => $data->id_semester, 'nama_semester' => $data->nama_semester, 'status_sync' => 'Belum Sync']);
            }else{
                if($data_nilai->feeder == 0){
                    $data_nilai->update(['nilai_angka' => $nilai_langsung, 'nilai_indeks' => $nilai_indeks, 'nilai_huruf' => $nilai_huruf]);
                }else{
                    return redirect()->back()->with('error', 'Nilai sudah di sinkronisasi.');
                }  
            }
            

            DB::commit();

            return redirect()->back()->with('success', 'Data Berhasil di Tambahkan');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ' . $th->getMessage());
        }
    }

    public function nilai_perkuliahan($id_reg_mhs)
    {
        $mahasiswa = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();
        $aktivitas_kuliah=AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','desc')->get();
        $nilai_transfer=NilaiTransferPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','asc')->get();
        $nilai_konversi=KonversiAktivitas::leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_anggota', 'konversi_aktivitas.id_anggota')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'konversi_aktivitas.id_matkul')
                        ->where('id_registrasi_mahasiswa',$id_reg_mhs)
                        ->orderBy('id_semester','asc')
                        ->get();


        $transkrip_mahasiswa=NilaiPerkuliahan::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','asc')->get();

        $total_sks_transfer = $nilai_transfer->whereNotIn('nilai_angka_diakui', [0, NULL] )->sum('sks_mata_kuliah_diakui');
        $total_sks_konversi = $nilai_konversi->whereNotIn('nilai_indeks', [0, NULL] )->sum('sks_mata_kuliah');
        $total_sks_transkrip = $transkrip_mahasiswa->whereNotIn('nilai_indeks', [0, NULL] )->sum('sks_mata_kuliah');

        $total_sks = $total_sks_transfer + $total_sks_konversi + $total_sks_transkrip ;
        // dd($total_sks);


        return view('dosen.pembimbing.akademik.khs', ['mahasiswa' => $mahasiswa,'data_aktivitas' => $aktivitas_kuliah, 'transkrip' => $transkrip_mahasiswa, 'nilai_konversi' => $nilai_konversi, 'nilai_transfer' => $nilai_transfer, 'total_sks'=>$total_sks]);
    }

    public function lihat_khs($id_reg_mhs, $id_semester)
    {
        $mahasiswa = RiwayatPendidikan::where('id_registrasi_mahasiswa', $id_reg_mhs)->first();
        $aktivitas_kuliah=AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa',$id_reg_mhs)->where('id_semester', $id_semester)->get();

        $nilai_mahasiswa = NilaiPerkuliahan::with(['dosen_pengajar', 'kelas_kuliah' => function($query) use ($id_reg_mhs) {
                                                $query->withCount(['kuisoner' => function($query) use ($id_reg_mhs) {
                                                    $query->where('id_registrasi_mahasiswa', $id_reg_mhs);
                                                }]);
                                            }])
                                            ->where('id_registrasi_mahasiswa', $id_reg_mhs)
                                            ->where('id_semester', $id_semester)
                                            ->orderBy('nama_mata_kuliah','asc')->get();

        // dd($nilai_mahasiswa);
        $transkrip_mahasiswa=NilaiPerkuliahan::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','asc')->get();
        $nilai_transfer=NilaiTransferPendidikan::where('id_registrasi_mahasiswa',$id_reg_mhs)->orderBy('id_semester','asc')->get();
        $nilai_konversi=KonversiAktivitas::leftJoin('anggota_aktivitas_mahasiswas', 'anggota_aktivitas_mahasiswas.id_anggota', 'konversi_aktivitas.id_anggota')
                        ->leftJoin('mata_kuliahs', 'mata_kuliahs.id_matkul', 'konversi_aktivitas.id_matkul')
                        ->where('id_registrasi_mahasiswa',$id_reg_mhs)
                        ->orderBy('id_semester','asc')
                        ->get();

        $semester_aktif = SemesterAktif::first()->id_semester;
        // dd($count_kuisoner);
        return view('dosen.pembimbing.akademik.detail-khs', [
            'mahasiswa' => $mahasiswa,
            'data_nilai' => $nilai_mahasiswa,
            'data_aktivitas' => $aktivitas_kuliah,
            'transkrip' => $transkrip_mahasiswa,
            'nilai_konversi' => $nilai_konversi,
            'nilai_transfer' => $nilai_transfer,
            'semester_aktif' => $semester_aktif,
        ]);
    }
}
