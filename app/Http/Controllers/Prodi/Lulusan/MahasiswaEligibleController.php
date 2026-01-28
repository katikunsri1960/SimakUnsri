<?php

namespace App\Http\Controllers\Prodi\Lulusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wisuda;
use App\Models\BkuProgramStudi;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Referensi\PredikatKelulusan;
use App\Models\Connection\Usept;
use App\Models\Connection\CourseUsept;
use App\Models\Perkuliahan\ListKurikulum;
use App\Models\Perkuliahan\NilaiTransferPendidikan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use DateTime;

class MahasiswaEligibleController extends Controller
{
    public function index()
    {
        $prodi_id = auth()->user()->fk_id;

        // Define required SKS for each "jenjang_pendidikan"
        $requiredSks = [
            'D3'  => 108,
            'S1'  => 144,
            'S2'  => 36,
            'S3'  => 42,
            'Profesi' => 24,
            'Sp-1' => 92,
            'Sp-2' => 42
        ];

        // Define required Masa Studi for each "jenjang_pendidikan"
        $requiredMasaStudi = [
            'D3'  => 5,
            'S1'  => 7,
            'S2'  => 4,
            'S3'  => 7,
            'Profesi' => 5,
            'Sp-1' => 8,
            'Sp-2' => 6
        ];

        // Define required Masa Studi for each "jenjang_pendidikan"
        $requiredIPK = [
            'D3'  => 2,
            'S1'  => 2,
            'S2'  => 3,
            'S3'  => 3,
            'Profesi' => 3,
            'Sp-1' => 3,
            'Sp-2' => 3
        ];

        $data = Wisuda::with([
                'riwayat_pendidikan',
                'lulus_do',
                'transkrip_mahasiswa',
                'aktivitas_kuliah',
                'prodi', // Assuming 'prodi' contains 'jenjang_pendidikan'
                'aktivitas_mahasiswa.nilai_konversi',
                'aktivitas_mahasiswa.semester',
                'bebas_pustaka'
            ])
            ->where('id_prodi', $prodi_id)
            ->whereHas('aktivitas_mahasiswa.nilai_konversi', function ($query) {
                $query->where('nilai_angka', '!=', 0);
            })
            ->withSum('transkrip_mahasiswa', 'sks_mata_kuliah')
            ->withSum('aktivitas_kuliah', 'sks_semester')
            ->orderBy('nim', 'ASC')
            ->get();

            // dd($data);
        // Add eligibility status to each student
        foreach ($data as $d) {
            $jenjang = $d->prodi->nama_jenjang_pendidikan ?? 'Unknown'; // Get jenjang from prodi
            $sks_transkrip= $d->transkrip_mahasiswa_sum_sks_mata_kuliah ?? 0; // Get summed SKS Transkrip
            $sks_akm= $d->aktivitas_kuliah_sum_sks_semester ?? 0; // Get summed SKS AKM
            $tanggal_masuk = $d->riwayat_pendidikan->tanggal_daftar ?? '1970-01-01';
            $tanggal_keluar = $d->lulus_do->tgl_sk_yudisium ?? '1970-01-01';
            $akm_terakhir = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)->orderBy('id_semester', 'desc')->first();
            $temp = 0;

            if($d->riwayat_pendidikan->id_jenis_daftar == 16 || $d->riwayat_pendidikan->id_jenis_daftar == 2 || $d->riwayat_pendidikan->id_jenis_daftar == 8){
                $kampus_merdeka = $d->aktivitas_kuliah->where('id_status_mahasiswa', 'M')->first();

                if($kampus_merdeka){
                    // Check if SKS is below required value
                    $d->jumlah_sks = isset($requiredSks[$jenjang]) && ($akm_terakhir->sks_total >= $requiredSks[$jenjang] && $akm_terakhir->sks_total == $sks_transkrip) ? '1' : '0';
                }else{
                    $sks_nilai_transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)->sum('sks_mata_kuliah_diakui');

                    $sks_total = $sks_akm + $sks_nilai_transfer;

                    $d->jumlah_sks = isset($requiredSks[$jenjang]) && ($sks_total >= $requiredSks[$jenjang] && $sks_total >= $sks_transkrip) ? '1' : '0';
                }
            }else{
                $kampus_merdeka = $d->aktivitas_kuliah->where('id_status_mahasiswa', 'M')->first();

                if($kampus_merdeka){
                    // Check if SKS is below required value
                    $d->jumlah_sks = isset($requiredSks[$jenjang]) && ($akm_terakhir->sks_total >= $requiredSks[$jenjang] && $akm_terakhir->sks_total == $sks_transkrip) ? '1' : '0';
                }else{
                    $d->jumlah_sks = isset($requiredSks[$jenjang]) && ($sks_akm >= $requiredSks[$jenjang] && $sks_akm >= $sks_transkrip) ? '1' : '0';
                }
            }

            $hitung_masa_studi = $this->getYearMonthDifference($tanggal_masuk, $tanggal_keluar);

            $d->status_masa_studi = isset($requiredMasaStudi[$jenjang]) && $hitung_masa_studi <= $requiredMasaStudi[$jenjang] ? '1' : '0';

            foreach($d->transkrip_mahasiswa as $dt){
                $temp = $temp + ($dt->nilai_indeks * $dt->sks_mata_kuliah);
                
                $d->ipk = round($temp / $sks_transkrip, 2); // normalized to 2 decimals

                // Exact comparison after rounding
                // $data->ipk_transkrip_akm = ($data->ipk === round($akm_terakhir->ipk, 2)) ? '1' : '0';

                $d->status_ipk = isset($requiredIPK[$jenjang]) && $akm_terakhir->ipk >= $requiredIPK[$jenjang] ? '1' : '0';
            }

            $akm_semester_pendek = AktivitasKuliahMahasiswa::whereRaw("RIGHT(id_semester, 1) = '3'")->where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)->sum('sks_semester');

            $d->status_semester_pendek = $akm_semester_pendek <= 9 ? '1' : '0';
        }

        // dd($data);
        return view('prodi.data-lulusan.mahasiswa-eligible', ['data' => $data ]);
    }

    public function update_foto(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'id' => 'required',
            'pas_foto' => 'required|image|mimes:jpg,jpeg,png|max:512',
        ], [
            'pas_foto.max' => 'Ukuran pas foto maksimal 500 KB.',
        ]);

        $wisuda = Wisuda::findOrFail($request->id);

        if ($wisuda->approved >=1) {
            return back()->with('error', 'Ajuan telah di Approve, Silahkan hubungi Direktorat Akademik untuk melakukan perbaikan.');
        }

        // Hapus foto lama
        if ($wisuda->pas_foto && Storage::disk('public')->exists($wisuda->pas_foto)) {
            Storage::disk('public')->delete($wisuda->pas_foto);
        }

        // Nama file custom
        $pasFotoName = 'pas_foto_' . str_replace(' ', '_', $wisuda->nim) . '.' .
            $request->file('pas_foto')->getClientOriginalExtension();

        // Simpan foto baru (PATH BENAR)
        $path = $request->file('pas_foto')
            ->storeAs('wisuda/pas_foto', $pasFotoName, 'public');

        // Update DB
        $wisuda->update([
            'pas_foto' => $path
        ]);

        return back()->with('success', 'Foto berhasil diperbarui');
    }

    public function detail_mahasiswa($id)
    { 
        $prodi_id = auth()->user()->fk_id;

        // Define required SKS for each "jenjang_pendidikan"
        $requiredSks = [
            'D3'  => 108,
            'S1'  => 144,
            'S2'  => 36,
            'S3'  => 42,
            'Profesi' => 24,
            'Sp-1' => 86,
            'Sp-2' => 70
        ];

        // Define required Masa Studi for each "jenjang_pendidikan"
        $requiredMasaStudi = [
            'D3'  => 4,
            'S1'  => 7,
            'S2'  => 4,
            'S3'  => 6,
            'Profesi' => 5,
            'Sp-1' => 7,
            'Sp-2' => 4
        ];

        // Define required Masa Studi for each "jenjang_pendidikan"
        $requiredIPK = [
            'D3'  => 2,
            'S1'  => 2,
            'S2'  => 3,
            'S3'  => 3,
            'Profesi' => 3,
            'Sp-1' => 3,
            'Sp-2' => 3
        ];

        $data = Wisuda::with([
                'riwayat_pendidikan',
                'lulus_do',
                'transkrip_mahasiswa',
                'aktivitas_kuliah',
                'aktivitas_mahasiswa',
                'predikat_kelulusan',
                'bku_prodi',
                'prodi', // Assuming 'prodi' contains 'jenjang_pendidikan'
                'riwayat_pendidikan.biodata',
                'aktivitas_mahasiswa.nilai_konversi',
                'aktivitas_mahasiswa.semester'
            ])
            ->where('id', $id)
            ->whereHas('aktivitas_mahasiswa.nilai_konversi', function ($query) {
                $query->where('nilai_angka', '!=', 0);
            })
            ->withSum('transkrip_mahasiswa', 'sks_mata_kuliah')
            ->withSum('aktivitas_kuliah', 'sks_semester')
            ->first();

        // dd($data);

        // Add eligibility status to each student
        $jenjang = $data->prodi->nama_jenjang_pendidikan ?? 'Unknown'; // Get jenjang from prodi
        $sks_transkrip= $data->transkrip_mahasiswa_sum_sks_mata_kuliah ?? 0; // Get summed SKS Transkrip
        $sks_akm= $data->aktivitas_kuliah_sum_sks_semester ?? 0; // Get summed SKS AKM
        $tanggal_masuk = $data->riwayat_pendidikan->tanggal_daftar ?? '1970-01-01';
        $tanggal_keluar = $data->lulus_do->tgl_sk_yudisium ?? '1970-01-01';
        $akm_terakhir = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $data->id_registrasi_mahasiswa)->orderBy('id_semester', 'desc')->first();
        $nilai_usept_prodi = ListKurikulum::where(
            'id_kurikulum',
            $data->riwayat_pendidikan->id_kurikulum
        )->first();
        $nilai_usept_mhs = Usept::whereIn(
            'nim',
            [$data->nim, $data->riwayat_pendidikan->biodata->nik]
        )->max('score');
        $temp = 0;

        // Ambil nilai course USEPT
        $nilai_course = CourseUsept::whereIn(
            'nim',
            [$data->nim, $data->riwayat_pendidikan->biodata->nik]
        )->get();

        // Instance object (WAJIB karena method non-static)
        $db_course_usept = new CourseUsept();

        $nilai_course_max = 0;

        foreach ($nilai_course as $n) {
            $hasil = $db_course_usept->KonversiNilaiUsept(
                $n->grade,
                $n->total_score
            );

            $nilai_course_max = max($nilai_course_max, $hasil);
        }

        // Ambil nilai USEPT tertinggi (USEPT murni vs course)
        $nilai_usept_final = max(
            $nilai_usept_mhs ?? 0,
            $nilai_course_max
        );

        // Status kelulusan USEPT
        $status_usept = $nilai_usept_final >= $nilai_usept_prodi->nilai_usept ? 1 : 0;




        if($data->riwayat_pendidikan->id_jenis_daftar == 16 || $data->riwayat_pendidikan->id_jenis_daftar == 2 || $data->riwayat_pendidikan->id_jenis_daftar == 8){
            $kampus_merdeka = $data->aktivitas_kuliah->where('id_status_mahasiswa', 'M')->first();

            if($kampus_merdeka){
                // Check if SKS is below required value
                $data->jumlah_sks = isset($requiredSks[$jenjang]) && $akm_terakhir->sks_total >= $requiredSks[$jenjang] ? '1' : '0';

                $data->sks_transkrip_akm = $akm_terakhir->sks_total == $sks_transkrip ? '1' : '0';
            }else{
                $sks_nilai_transfer = NilaiTransferPendidikan::where('id_registrasi_mahasiswa', $data->id_registrasi_mahasiswa)->sum('sks_mata_kuliah_diakui');

                $sks_total = $sks_akm + $sks_nilai_transfer;

                $data->jumlah_sks = isset($requiredSks[$jenjang]) && $sks_total >= $requiredSks[$jenjang] ? '1' : '0';

                $data->sks_transkrip_akm = $akm_terakhir->sks_total == $sks_transkrip ? '1' : '0';
            }
        }else{
            $kampus_merdeka = $data->aktivitas_kuliah->where('id_status_mahasiswa', 'M')->first();

            if($kampus_merdeka){
                // Check if SKS is below required value
                $data->jumlah_sks = isset($requiredSks[$jenjang]) && $akm_terakhir->sks_total >= $requiredSks[$jenjang] ? '1' : '0';

                $data->sks_transkrip_akm = $akm_terakhir->sks_total == $sks_transkrip ? '1' : '0';
            }else{
                $data->jumlah_sks = isset($requiredSks[$jenjang]) && $sks_akm >= $requiredSks[$jenjang] ? '1' : '0';

                $data->sks_transkrip_akm = $akm_terakhir->sks_total == $sks_transkrip ? '1' : '0';
            }
        }

        $hitung_masa_studi = $this->getYearMonthDifference($tanggal_masuk, $tanggal_keluar);

        $data->status_masa_studi = isset($requiredMasaStudi[$jenjang]) && $hitung_masa_studi <= $requiredMasaStudi[$jenjang] ? '1' : '0';

        foreach ($data->transkrip_mahasiswa as $dt) {
            $temp += ($dt->nilai_indeks * $dt->sks_mata_kuliah);

            $data->ipk = round($temp / $sks_transkrip, 2); // normalized to 2 decimals

            // Exact comparison after rounding
            $data->ipk_transkrip_akm = ($data->ipk === round($akm_terakhir->ipk, 2)) ? '1' : '0';

            $data->status_ipk = isset($requiredIPK[$jenjang]) && $akm_terakhir->ipk >= $requiredIPK[$jenjang] ? '1' : '0';
        }


        $akm_semester_pendek = AktivitasKuliahMahasiswa::whereRaw("RIGHT(id_semester, 1) = '3'")->where('id_registrasi_mahasiswa', $data->id_registrasi_mahasiswa)->sum('sks_semester');

        $data->status_semester_pendek = $akm_semester_pendek <= 9 ? '1' : '0'; 

        $predikat_kelulusan = PredikatKelulusan::orderBy('id')->get();
        $bku = BkuProgramStudi::orderBy('id')->where('id_prodi', $prodi_id)->get();

        // dd($data);

        return view('prodi.data-lulusan.detail-mahasiswa', ['data' => $data, 'bku' => $bku, 'predikat_kelulusan' => $predikat_kelulusan, 'nilai_usept' => $nilai_usept_final, 'status_usept' => $status_usept]);
    }

    public function update_detail_mahasiswa(Request $request, $id)
    {
        $data = $request->validate([
            'judul_en' => 'required',
            // 'predikat_mhs' => 'required',
            'bku_mhs' => 'nullable'
        ]);

        // dd($data);
        try {
            DB::beginTransaction();

                //Update detail ajuan wisuda by prodi
                Wisuda::where('id', $id)->update(['judul_eng' => $request->judul_en, 'id_bku_prodi' => $request->bku_mhs]);

            DB::commit();

            return redirect()->route('prodi.data-lulusan.detail', $id)->with('success', 'Data Berhasil di Setujui');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. Error: ' . $th->getMessage());
        }
    }

    public function approved_ajuan(Request $request, $id)
    {
        // dd($request->all());
        $data = $request->validate([
            'agreement' => 'required',
            // 'predikat_mhs' => 'required'
        ]);

        // dd($request->agreement);
        try {
            DB::beginTransaction();

            if($request->agreement == 1){
                //Update approved data by prodi
                Wisuda::where('id', $id)->update(['approved' => 1]);
            }

            DB::commit();

            // return redirect()->route('prodi.data-lulusan.detail', $id)->with('success', 'Data Berhasil di Setujui');
            return redirect()->route('prodi.data-lulusan.index')->with('success', 'Data Berhasil di Setujui');

        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', 'Data Gagal di Tambahkan. ');
        }
    }

    public function decline_ajuan(Request $request, $id)
    {
        $data = $request->validate([
            'alasan_pembatalan' => 'required'
        ]);

        Wisuda::where('id',$id)->update([
            'approved' => 97,
            'alasan_pembatalan' => $request->alasan_pembatalan
        ]);

        return redirect()->back()->with('success', 'Data berhasil dibatalkan');
    }

    private function getYearMonthDifference($tanggal_masuk, $tanggal_keluar) {
        $start = new DateTime($tanggal_masuk);
        $end = new DateTime($tanggal_keluar);
        $diff = $start->diff($end);

        // Calculate total months
        $totalMonths = ($diff->y * 12) + $diff->m;

        // Convert to years and decimal months
        $years = floor($totalMonths / 12);
        $months = $totalMonths % 12;
        $decimalMonths = round($months / 12, 1); // Convert months to decimal

        return ($years + $decimalMonths);
    }
}
