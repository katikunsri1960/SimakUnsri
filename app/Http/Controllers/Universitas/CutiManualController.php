<?php

namespace App\Http\Controllers\Universitas;

use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Semester;
use App\Models\SOManual;
use Illuminate\Http\Request;
use App\Models\BeasiswaMahasiswa;
use App\Models\Connection\Tagihan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Connection\Registrasi;
use App\Models\Perkuliahan\MataKuliah;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasMahasiswa;
use App\Models\Perkuliahan\PesertaKelasKuliah;
use App\Models\Perkuliahan\TranskripMahasiswa;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;

class CutiManualController extends Controller
{
    public function index(Request $request)
    {
        $data = SOManual::with('riwayat')->get();  // Optimasi query eager loading
        $semester = Semester::orderBy('id_semester', 'desc')->get();

        return view('universitas.cuti-manual.index', [
            'semester' => $semester,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_registrasi_mahasiswa' => 'required|exists:riwayat_pendidikans,id_registrasi_mahasiswa',
            'id_semester' => 'required|exists:semesters,id_semester',
            'tanggal_sk' => 'nullable|date',
            'alasan_cuti' => 'required|string|max:255',
            'no_sk' => 'nullable|string|max:50'
        ]);

        DB::beginTransaction(); // Mulai transaction

        try {
            // Cek apakah mahasiswa sudah mengajukan cuti di semester ini
            $existingCuti = SOManual::where('id_registrasi_mahasiswa', $validatedData['id_registrasi_mahasiswa'])
                ->where('id_semester', $validatedData['id_semester'])
                ->first();

            if ($existingCuti) {
                return redirect()->back()->withErrors('Mahasiswa sudah mengajukan cuti untuk semester ini.');
            }

            $riwayat = RiwayatPendidikan::where('id_registrasi_mahasiswa', $validatedData['id_registrasi_mahasiswa'])->firstOrFail();
            $semester = Semester::where('id_semester', $validatedData['id_semester'])->first();

            $id_cuti = Uuid::uuid4()->toString();

            $data = array_merge($validatedData, [
                'id_cuti' => $id_cuti,
                'nim' => $riwayat->nim,
                'handphone' => $request->handphone ?? null,
                'nama_mahasiswa' => $riwayat->nama_mahasiswa,
                'id_prodi' => $riwayat->id_prodi,
                'nama_semester' => $semester->nama_semester,
                'file_pendukung' => 'Dibuat Manual',
                'approved' => 3,
                'status_sync' => 'belum sync',
            ]);

            // Simpan data cuti
            SOManual::create($data);

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
                        ->where('id_semester', $semester->id_semester)
                        ->whereIn('id_jenis_aktivitas',['13','14','15','16','17','18','19','20', '21'])
                        ->get();

            list($krs_akt, $data_akt_ids) = $db_akt->getKrsAkt($validatedData['id_registrasi_mahasiswa'], $semester->id_semester);

            $sks_max = $db->getSksMax($validatedData['id_registrasi_mahasiswa'], $semester->id_semester, $riwayat_pendidikan->id_periode_masuk);

            $krs_regular = $db->getKrsRegular($validatedData['id_registrasi_mahasiswa'], $riwayat_pendidikan, $semester->id_semester, $data_akt_ids);

            $krs_merdeka = $db->getKrsMerdeka($validatedData['id_registrasi_mahasiswa'], $semester->id_semester, $riwayat_pendidikan->id_prodi);

            $total_sks_akt = $krs_akt->sum('konversi.sks_mata_kuliah');
            $total_sks_merdeka = $krs_merdeka->sum('sks_mata_kuliah');
            $total_sks_regular = $krs_regular->sum('sks_mata_kuliah');
            $total_sks_mbkm = $krs_aktivitas_mbkm->sum('sks_aktivitas');

            $total_sks = $total_sks_regular + $total_sks_merdeka + $total_sks_akt + $total_sks_mbkm;

            $transkrip = TranskripMahasiswa::select(
                            DB::raw('SUM(CAST(sks_mata_kuliah AS UNSIGNED)) as total_sks'), // Mengambil total SKS tanpa nilai desimal
                            DB::raw('ROUND(SUM(nilai_indeks * sks_mata_kuliah) / SUM(sks_mata_kuliah), 2) as ipk') // Mengambil IPK dengan 2 angka di belakang koma
                        )
                        ->where('id_registrasi_mahasiswa', $validatedData['id_registrasi_mahasiswa'])
                        ->whereNotIn('nilai_huruf', ['F', ''])
                        ->groupBy('id_registrasi_mahasiswa')
                        ->first();

            // Jika approved == 3, buat data di tabel aktivitas_kuliah_mahasiswas
            if ($data['approved'] == 3) {
                AktivitasKuliahMahasiswa::create([
                    'feeder' => 0,
                    'id_registrasi_mahasiswa' => $riwayat->id_registrasi_mahasiswa,
                    'nim' => $riwayat->nim,
                    'nama_mahasiswa' => $riwayat->nama_mahasiswa,
                    'id_prodi' => $riwayat->id_prodi,
                    'nama_program_studi' => $riwayat->nama_program_studi,
                    'angkatan' => substr($riwayat->id_periode_masuk, 0, 4), // 4 angka paling kiri dari id_periode_masuk
                    'id_periode_masuk' => $riwayat->id_periode_masuk ?? null,
                    'id_semester' => $semester->id_semester,
                    'nama_semester' => $semester->nama_semester,
                    'id_status_mahasiswa' => 'C', // Ganti dengan single quote
                    'nama_status_mahasiswa' => 'Cuti', // Ganti dengan single quote
                    'ips' => '0.00', // Ganti dengan single quote
                    'ipk' => $transkrip->ipk == null && $riwayat_pendidikan->id_periode_masuk == $semester->id_semester ? 0 : $transkrip->ipk,
                    'sks_semester' => $total_sks,
                    'sks_total' => $transkrip->total_sks == null && $riwayat_pendidikan->id_periode_masuk == $semester->id_semester ? 0 : $transkrip->total_sks,
                    'biaya_kuliah_smt' => 0,
                    'id_pembiayaan' => 1,
                    'status_sync' => 'belum sync',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            

            DB::commit(); // Commit jika semua berhasil

            return redirect()->back()->with('success', 'Data berhasil disimpan.');
        } catch (Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            return redirect()->back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }



    public function update(SOManual $idmanual, Request $request)
    {
        
    }

    public function destroy(SOManual $idmanual)
    {
        try {
            $idmanual->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
