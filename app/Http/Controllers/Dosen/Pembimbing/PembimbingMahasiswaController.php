<?php

namespace App\Http\Controllers\Dosen\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\AsistensiAkhir;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\AnggotaAktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\UjiMahasiswa;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Connection\Usept;
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
        $semester = SemesterAktif::first();
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

        $aktivitas = $aktivitas->load(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi']);

        $pembimbing_ke = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
                            ->where('id_dosen', auth()->user()->fk_id)
                            ->first()->pembimbing_ke;
        // dd($pembimbing_ke);
        return view('dosen.pembimbing.tugas-akhir.asistensi', [
            'data' => $data,
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
        $query = PenugasanDosen::where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1)
                                ->orderBy('nama_dosen', 'asc');

        // Add search conditions if search term is present
        if ($search) {
            $query->where(function ($q) use ($search, $tahun_ajaran) {
                $q->where('nama_dosen', 'like', "%{$search}%")
                ->orWhere('nama_program_studi', 'like', "%{$search}%")
                ->where('id_tahun_ajaran', $tahun_ajaran->semester->id_tahun_ajaran-1);
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

            $aktivitasMahasiswa = AktivitasMahasiswa::find($aktivitas);
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

            $nilai_usept_prodi = ListKurikulum::where('id_kurikulum', $data_mahasiswa->mahasiswa->id_kurikulum)->first();
            $nilai_usept_mhs = Usept::whereIn('nim', [$data_mahasiswa->nim, $data_mahasiswa->mahasiswa->biodata->nik])->max('score');

            // dd($nilai_usept_mhs);
            
            if ($nilai_usept_mhs >= $nilai_usept_prodi->nilai_usept) {
                // dd('masuk');
                $aktivitasMahasiswa->update(['judul' => $data['judul'], 'approve_sidang' => 1]);
                $data_mahasiswa->update(['judul' => $data['judul']]);

                foreach ($bimbingMahasiswa as $b) {
                    $b->update(['judul' => $data['judul']]);
                }

                if($ujiMahasiswa){
                    foreach ($ujiMahasiswa as $u) {
                        $u->update(['judul' => $data['judul']]);
                    }
                }

            }else{
                return redirect()->back()->with('error', 'Mahasiswa belum menyelesaikan syarat kelulusan nilai USEPT.');
            }

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

                    $kategori_kegiatan = $request->penguji_ke[$i] == '1' ? '110501' : '110502';
                    $nama_kategori_kegiatan = $request->penguji_ke[$i] == '1' ? 'Ketua Penguji' : 'Anggota Penguji';

                    UjiMahasiswa::create([
                        'feeder' => 0,
                        'id_uji' => $id_uji_mahasiswa,
                        'id_aktivitas' => $aktivitas,
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
        // dd($data);
        return view('dosen.pembimbing.non-tugas-akhir.index', [
            'data' => $data,
            'semester' => $semester,
            'id_semester' => $id_semester,
        ]);
    } 
}
