<?php

namespace App\Http\Controllers\Universitas;

use Ramsey\Uuid\Uuid;
use App\Models\Semester;
use App\Models\CutiManual;
use Illuminate\Http\Request;
use App\Models\SemesterAktif;
use App\Models\BeasiswaMahasiswa;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Mahasiswa\PengajuanCuti;
use Illuminate\Support\Facades\Storage;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class CutiManualController extends Controller
{
    public function index(Request $request)
    {
        $db = new PengajuanCuti;

        $data = $db->with(['riwayat', 'prodi']);

        if ($request->has('semester')) {
            $data = $data->where('id_semester', $request->semester);
        }

        $data = $data->get();
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        // dd($data);
        return view('universitas.cuti-manual.index', [
            'semester' => $semester,
            'data' => $data,
        ]);
    }

    public function get_mahasiswa(Request $request)
    {
        $db = new RiwayatPendidikan();

        $data = $db->with('biodata', 'prodi')
                    ->where('nim', 'like', '%'.$request->q.'%')
                    ->orWhere('nama_mahasiswa', 'like', '%'.$request->q.'%')
                    ->orderBy('id_periode_masuk', 'desc')->get();

       // Menambahkan semester aktif ke dalam data response
        return response()->json([
            'data' => $data
        ]);
    }
    
    public function getMahasiswaData($id_registrasi_mahasiswa)
    {
        $mahasiswa = RiwayatPendidikan::with('biodata', 'prodi', 'prodi.fakultas', 'prodi.jurusan')
            ->where('id_registrasi_mahasiswa', $id_registrasi_mahasiswa)
            ->first();

        // Ambil semester aktif
        $semester_aktif = SemesterAktif::with('semester')->first();

        // Mengirim data mahasiswa dan semester aktif
        return response()->json([
            'data' => $mahasiswa,
            'semester_aktif' => $semester_aktif
        ]);
    }

    public function store(Request $request)
    { 
        $validatedData = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'tanggal_sk' => 'nullable|date',
            'alasan_cuti' => 'required|string|max:255',
            'no_sk' => 'nullable|string|max:50'
        ]);

        try {
            DB::beginTransaction();
            // dd($request->tanggal_sk, $request->no_sk);
            // Validate request data
            
            // Define variable
            $id_reg = $request->id_registrasi_mahasiswa;
            $semester_aktif=SemesterAktif::with('semester')->first();
            
            $riwayat_pendidikan = RiwayatPendidikan::with('biodata')
                        ->where('id_registrasi_mahasiswa', $id_reg)
                        ->first();
            // dd($semester_aktif);
            // Cek apakah sudah ada pengajuan cuti yang sedang diproses
            $existingCuti = PengajuanCuti::where('id_registrasi_mahasiswa', $id_reg)
                        ->where('id_semester', $semester_aktif->id_semester)
                        ->first();

            // Jika sudah ada pengajuan cuti yang sedang diproses, tampilkan pesan error
            if (!empty($existingCuti)) {
                if ($existingCuti->approved == 0) {
                    return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sedang diproses. Tunggu persetujuan atau batalkan pengajuan sebelum membuat pengajuan baru.');
                } elseif ($existingCuti->approved == 1 || $existingCuti->approved == 2 || $existingCuti->approved == 3) {
                    return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan cuti yang sudah disetujui.');
                }
            }

            $id_cuti = Uuid::uuid4()->toString();

            $alamat = $riwayat_pendidikan->biodata->jalan . ', ' . $riwayat_pendidikan->biodata->dusun . ', RT-' . $riwayat_pendidikan->biodata->rt . '/RW-' . $riwayat_pendidikan->biodata->rw
            . ', ' . $riwayat_pendidikan->biodata->kelurahan . ', ' . $riwayat_pendidikan->biodata->nama_wilayah;

            $alamat = str_replace(', ,', ',', $alamat);

            // dd($alamat);
            $alasan= $request->alasan_cuti;

            if (!$alasan) {
                $alasan = 'Alasan tidak diisi';
            }

            // dd($alasan);

            // Cek apakah ada file yang diunggah
            if ($request->hasFile('file_pendukung')) {
                // Generate file name
                $fileName = 'file_pendukung_' . str_replace(' ', '_', $riwayat_pendidikan->nama_mahasiswa) . '_' . time() . '.' . $request->file('file_pendukung')->getClientOriginalExtension();
                // Simpan file ke folder public/pdf dengan nama kustom
                $filePath = $request->file('file_pendukung')->storeAs('pdf', $fileName, 'public');
            } else {
                // Jika file tidak diunggah, gunakan nama default
                $filePath = 'pdf/tidak_ada_file.pdf';
            }

            // Cek apakah file berhasil diupload
            if (!$filePath) {
                return redirect()->back()->with('error', 'File pendukung gagal diunggah. Silakan coba lagi.');
            }

            $data = array_merge($validatedData, [
                'id_cuti' => $id_cuti,
                'id_registrasi_mahasiswa' => $id_reg,
                'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                'nim'=>$riwayat_pendidikan->nim,
                'id_semester' => $semester_aktif->id_semester,
                'nama_semester'=> $semester_aktif->semester->nama_semester,
                'id_prodi'=>$riwayat_pendidikan->id_prodi,
                'alamat'=> $alamat,
                'tanggal_sk'=>$request->tanggal_sk,
                'no_sk'=>$request->no_sk,
                'handphone' => $riwayat_pendidikan->biodata->handphone,
                'alasan_cuti' => $alasan,
                'file_pendukung' => $filePath,
                'approved' => 3,
                'status_sync' => 'belum sync',
            ]);

            // Simpan data cuti

            CutiManual::create($data);

            // dd($data['approved']);
            // Jika approved == 3, buat data di tabel aktivitas_kuliah_mahasiswas
            if ($data['approved'] == 3) {

                $beasiswa = BeasiswaMahasiswa::where('id_registrasi_mahasiswa', $validatedData['id_registrasi_mahasiswa'])->count();

                if ($beasiswa > 0) {
                    return redirect()->back()->withErrors('Mahasiswa adalah penerima Beasiswa, Tidak bisa mengajukan cuti untuk!');
                }

                $db = new MataKuliah();
                $db_akt = new AktivitasMahasiswa();

                $riwayat_pendidikan = RiwayatPendidikan::select('riwayat_pendidikans.*', 'biodata_dosens.id_dosen', 'biodata_dosens.nama_dosen')
                        ->where('id_registrasi_mahasiswa', $validatedData['id_registrasi_mahasiswa'])
                        ->leftJoin('biodata_dosens', 'biodata_dosens.id_dosen', '=', 'riwayat_pendidikans.dosen_pa')
                        ->first();

                $krs_aktivitas_mbkm = AktivitasMahasiswa::with(['anggota_aktivitas'])
                            ->whereHas('anggota_aktivitas' , function($query) use ($validatedData) {
                                    $query->where('id_registrasi_mahasiswa', $validatedData['id_registrasi_mahasiswa']);
                            })
                            // ->where('approve_krs', 1)
                            ->where('id_semester', $semester_aktif->id_semester)
                            ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20', '21'])
                            ->get();

                list($krs_akt, $data_akt_ids) = $db_akt->getKrsAkt($validatedData['id_registrasi_mahasiswa'], $semester_aktif->id_semester);

                $sks_max = $db->getSksMax($validatedData['id_registrasi_mahasiswa'], $semester_aktif->id_semester, $riwayat_pendidikan->id_periode_masuk);

                $krs_regular = $db->getKrsRegular($validatedData['id_registrasi_mahasiswa'], $riwayat_pendidikan, $semester_aktif->id_semester, $data_akt_ids);

                $krs_merdeka = $db->getKrsMerdeka($validatedData['id_registrasi_mahasiswa'], $semester_aktif->id_semester, $riwayat_pendidikan->id_prodi);

                $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
                $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
                $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');
                $total_sks_mbkm = $krs_aktivitas_mbkm->sum('sks_aktivitas');

                $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt + $total_sks_mbkm;

                if ($total_sks > 0) {
                    return redirect()->back()->with('error','Pengajuan Cuti Tidak Diizinkan, Mahasiswa Telah Melakukan Pengisian KRS!');
                }

                $transkrip = TranskripMahasiswa::select(
                                DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                                DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
                            )
                            ->where('id_registrasi_mahasiswa', $validatedData['id_registrasi_mahasiswa'])
                            ->whereNotIn('nilai_huruf', ['F', ''])
                            ->groupBy('id_registrasi_mahasiswa')
                            ->first();
                            
                $akm = AktivitasKuliahMahasiswa::create([
                    'feeder' => 0,
                    'id_registrasi_mahasiswa' => $riwayat_pendidikan->id_registrasi_mahasiswa,
                    'nim' => $riwayat_pendidikan->nim,
                    'nama_mahasiswa' => $riwayat_pendidikan->nama_mahasiswa,
                    'id_prodi' => $riwayat_pendidikan->id_prodi,
                    'nama_program_studi' => $riwayat_pendidikan->nama_program_studi,
                    'angkatan' => substr($riwayat_pendidikan->id_periode_masuk, 0, 4), // 4 angka paling kiri dari id_periode_masuk
                    'id_periode_masuk' => $riwayat_pendidikan->id_periode_masuk ?? null,
                    'id_semester' => $semester_aktif->id_semester,
                    'nama_semester' => $semester_aktif->semester->nama_semester,
                    'id_status_mahasiswa' => 'C', // Ganti dengan single quote
                    'nama_status_mahasiswa' => 'Cuti', // Ganti dengan single quote
                    'ips' => '0.00', // Ganti dengan single quote
                    'ipk' => $transkrip->ipk == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->ipk,
                    'sks_semester' => $total_sks,
                    'sks_total' => $transkrip->total_sks == null && $riwayat_pendidikan->id_periode_masuk == $semester_aktif->id_semester ? 0 : $transkrip->total_sks,
                    'biaya_kuliah_smt' => 0,
                    'id_pembiayaan' => 1,
                    'status_sync' => 'belum sync',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // dd($akm);
            }

            DB::commit(); // Commit jika semua berhasil
            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('univ.cuti-kuliah')->with('success', 'Data Berhasil di Tambahkan');

        } catch (\Exception $e) {
            // Tampilkan pesan error jika ada masalah
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id_cuti)
    {
        try {
            $cuti = PengajuanCuti::where('id_cuti', $id_cuti)->first();

            // Cek apakah approved = 3
            if ($cuti->approved == 3) {
                return redirect()->back()->with('error','Data tidak dapat dihapus karena sudah disetujui BAK!');
            }

            // Temukan pengajuan cuti berdasarkan ID
            
            // dd($cuti);
            $akm = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $cuti->id_registrasi_mahasiswa)
                                ->where('id_semester', $cuti->id_semester)
                                ->first();

            // Jika pengajuan cuti tidak ditemukan, lemparkan pesan error
            if (!$cuti) {
                return redirect()->route('univ.cuti-kuliah')->with('error', 'Pengajuan cuti tidak ditemukan.');
            }



            // Hapus file pendukung dari storage jika ada
            // if ($cuti->file_pendukung) {
            //     \Storage::disk('public')->delete($cuti->file_pendukung);
            // }

            // Hapus data pengajuan cuti dari database
            $cuti->delete();

            $akm->delete();

            // Redirect kembali ke halaman index dengan pesan sukses
            return redirect()->route('univ.cuti-kuliah')->with('success', 'Pengajuan cuti berhasil dihapus.');
        } catch (\Exception $e) {
            // Tangani error dan tampilkan pesan error
            return redirect()->route('univ.cuti-kuliah')->with('error', 'Terjadi kesalahan saat menghapus pengajuan cuti.');
        }
    }

}
