<?php

namespace App\Http\Controllers\Prodi\Lulusan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use Illuminate\Support\Facades\DB;
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
            'Sp1' => 92,
            'Sp2' => 42
        ];

        // Define required Masa Studi for each "jenjang_pendidikan"
        $requiredMasaStudi = [
            'D3'  => 5,
            'S1'  => 7,
            'S2'  => 4,
            'S3'  => 7,
            'Profesi' => 5,
            'Sp1' => 8,
            'Sp2' => 6
        ];

        $data = RiwayatPendidikan::with([
                'transkrip_mahasiswa',
                'aktivitas_kuliah',
                'prodi', // Assuming 'prodi' contains 'jenjang_pendidikan'
                'aktivitas_mahasiswa.nilai_konversi', 
                'aktivitas_mahasiswa.semester'
            ])
            ->where('id_prodi', $prodi_id)
            ->whereNull('id_jenis_keluar')
            ->whereHas('aktivitas_mahasiswa', function ($query) {
                $query->whereIn('id_jenis_aktivitas', ['3', '4', '22']);
            })
            ->whereHas('aktivitas_mahasiswa.nilai_konversi', function ($query) {
                $query->where('nilai_angka', '!=', 0);
            })
            ->withSum('transkrip_mahasiswa', 'sks_mata_kuliah')
            ->withSum('aktivitas_kuliah', 'sks_semester')
            ->orderBy('nim', 'ASC')
            ->get();

        // Add eligibility status to each student
        foreach ($data as $d) {
            $jenjang = $d->prodi->nama_jenjang_pendidikan ?? 'Unknown'; // Get jenjang from prodi
            $sks_transkrip= $d->transkrip_mahasiswa_sum_sks_mata_kuliah ?? 0; // Get summed SKS Transkrip
            $sks_akm= $d->aktivitas_kuliah_sum_sks_semester ?? 0; // Get summed SKS AKM
            $tanggal_masuk = $d->tanggal_daftar ?? '1970-01-01';
            $akm_terakhir = AktivitasKuliahMahasiswa::where('id_registrasi_mahasiswa', $d->id_registrasi_mahasiswa)->orderBy('id_semester', 'desc')->first();
            $temp = 0;

            if($d->id_jenis_daftar == 16 || $d->id_jenis_daftar == 2 || $d->id_jenis_daftar == 8){
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

            $hitung_masa_studi = $this->getYearMonthDifference($tanggal_masuk);

            $d->masa_studi = isset($requiredMasaStudi[$jenjang]) && $hitung_masa_studi <= $requiredMasaStudi[$jenjang] ? '1' : '0';

            foreach($d->transkrip_mahasiswa as $dt){
                $temp = $temp + ($dt->nilai_indeks * $dt->sks_mata_kuliah);
                $d->ipk = $temp/$sks_transkrip;
                $d->status_ipk = $temp/$sks_transkrip == $akm_terakhir->ipk ? '1' : '0';
            }
        }

        dd($data);

        

        return view('prodi.data-lulusan.mahasiswa-eligible', ['data' => $data ]);
    }

    private function getYearMonthDifference($tanggal_masuk) {
        $start = new DateTime($tanggal_masuk);
        $end = new DateTime();
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
