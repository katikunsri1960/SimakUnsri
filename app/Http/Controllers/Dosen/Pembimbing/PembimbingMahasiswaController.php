<?php

namespace App\Http\Controllers\Dosen\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\AsistensiAkhir;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\BimbingMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Dosen\PenugasanDosen;
use App\Models\Semester;
use App\Models\SemesterAktif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $semester = SemesterAktif::first()->id_semester;
        $data = PesertaKelasKuliah::with(['kelas_kuliah', 'kelas_kuliah.matkul'])
                ->whereHas('kelas_kuliah', function($query) use ($semester) {
                    $query->where('id_semester', $semester);
                })
                ->where('id_registrasi_mahasiswa', $id)
                ->orderBy('kode_mata_kuliah')
                ->get();

        // dd($data);
        $aktivitas = AktivitasMahasiswa::with('anggota_aktivitas_personal', 'konversi')
                    ->whereHas('anggota_aktivitas_personal', function($query) use ($id) {
                        $query->where('id_registrasi_mahasiswa', $id);
                    })
                    ->where('id_semester', $semester)
                    ->get();

        return view('dosen.pembimbing.akademik.detail', [
            'riwayat' => $riwayat,
            'data' => $data,
            'aktivitas' => $aktivitas,
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
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
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
        $data = $aktivitas->load(['bimbing_mahasiswa', 'anggota_aktivitas_personal', 'prodi']);

        // dd($pembimbing_ke);
        return view('dosen.pembimbing.tugas-akhir.pengajuan-sidang', [
            'data' => $data
        ]);
    }

    public function get_dosen(Request $request)
    {
        $search = $request->get('q');

        // Log the search term for debugging
        \Log::info('Search term: ' . $search);

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
        // dd($request->all());

        try {
            DB::beginTransaction();

            AktivitasMahasiswa::update('approve_sidang');



            $pembimbing_ke = BimbingMahasiswa::where('id_aktivitas', $aktivitas->id_aktivitas)
                            ->where('id_dosen', auth()->user()->fk_id)
                            ->first()->pembimbing_ke;

            //Define variable
            $semester_aktif = SemesterAktif::first();

            //Validate request data
            $data = $request->validate([
                'dosen_kelas_kuliah.*' => 'required',
                'rencana_minggu_pertemuan.*' => 'required',
                'evaluasi.*' => [
                    'required',
                    Rule::in(['1','2','3','4'])
                ]
            ]);

            $dosen_pengajar = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->whereIn('id_registrasi_dosen', $request->dosen_kelas_kuliah)->get();

            if($dosen_pengajar->count() == 0 || $dosen_pengajar->count() != count($request->dosen_kelas_kuliah)){
                $dosen_pengajar = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->whereIn('id_registrasi_dosen', $request->dosen_kelas_kuliah)->get();
            }
            //Count jumlah dosen pengajar kelas kuliah
            $jumlah_dosen=count($request->dosen_kelas_kuliah);

            for($i=0;$i<$jumlah_dosen;$i++){
                //Generate id aktivitas mengajar
                $id_uji_mahasiswa = Uuid::uuid4()->toString();
                $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran)->where('id_registrasi_dosen', $request->dosen_kelas_kuliah[$i])->first();
                if(!$dosen)
                {
                    $dosen = PenugasanDosen::where('id_tahun_ajaran',$semester_aktif->semester->id_tahun_ajaran-1)->where('id_registrasi_dosen', $request->dosen_kelas_kuliah[$i])->first();
                }

                if(is_null($request->substansi_kuliah)){
                    //Store data to table tanpa substansi kuliah
                    DosenPengajarKelasKuliah::create(['feeder'=> 0,'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen->id_registrasi_dosen, 'id_dosen'=> $dosen->id_dosen, 'urutan' => $i+1, 'id_kelas_kuliah'=> $kelas[0]['id_kelas_kuliah'], 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);

                }else{
                    //Get sks substansi total
                    $substansi_kuliah = SubstansiKuliah::where('id_substansi',$request->substansi_kuliah[$i])->get();

                    //Store data to table dengan substansi kuliah
                    DosenPengajarKelasKuliah::create(['feeder'=> 0, 'id_aktivitas_mengajar'=> $id_aktivitas_mengajar, 'id_registrasi_dosen'=> $dosen->id_registrasi_dosen, 'id_dosen'=> $dosen->id_dosen, 'urutan' => $i+1, 'id_kelas_kuliah'=> $kelas[0]['id_kelas_kuliah'], 'id_substansi' => $substansi_kuliah->first()->id_substansi, 'sks_substansi_total' => $substansi_kuliah->first()->sks_mata_kuliah, 'rencana_minggu_pertemuan'=> $request->rencana_minggu_pertemuan[$i], 'realisasi_minggu_pertemuan'=> 0, 'id_jenis_evaluasi'=> $request->evaluasi[$i], 'id_prodi'=> $prodi_id, 'id_semester'=> $semester_aktif->id_semester]);
                }

            }

            DB::commit();

            return redirect()->route('prodi.data-akademik.kelas-penjadwalan.detail', $id_matkul)->with('success', 'Data Berhasil di Tambahkan');


            //
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. '. $th->getMessage());
        }

    }
}
