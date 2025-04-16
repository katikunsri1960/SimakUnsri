<?php

namespace App\Imports;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\Perkuliahan\AktivitasKuliahMahasiswa;
use App\Models\Semester;
use App\Models\StatusMahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AktivitasKuliahImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '2048M');

        try {
            DB::beginTransaction();

            foreach ($collection as $index => $row) {
                // dd($row);
                $mahasiswa = RiwayatPendidikan::where('nim', $row['nim'])->orderBy('id_periode_masuk', 'desc')->first();
                $status_mahasiswa = StatusMahasiswa::where('id_status_mahasiswa', $row['id_status_mahasiswa'])->first();
                $semester = Semester::where('id_semester', $row['id_semester'])->first();

                // dd($status_mahasiswa, $semester, $mahasiswa, $row);
                // cek tanggal_pembayaran
                // jika format adalah dd-mm-yyyy ganti menjadi yyyy-mm-dd
                // jika format adalah yyyy-mm-dd tidak perlu diuba
                // tanggal_pembayaran adalah date format di excel
                // $row['tanggal_pembayaran'] = date('Y-m-d', strtotime($row['tanggal_pembayaran']));

                if (! $mahasiswa) {
                    return back()->withErrors(["Data mahasiswa dengan NIM {$row['nim']} tidak ditemukan!!"]);
                }

                AktivitasKuliahMahasiswa::updateOrCreate(['id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa, 'id_semester' => $row['id_semester']], [
                    'feeder' => 0,
                    'nim' => $mahasiswa->nim,
                    'nama_mahasiswa' => $mahasiswa->nama_mahasiswa,
                    'id_prodi' => $mahasiswa->id_prodi,
                    'nama_program_studi' => $mahasiswa->nama_program_studi,
                    'angkatan' => substr($mahasiswa->id_periode_masuk, 0, 4),
                    'id_periode_masuk' => $mahasiswa->id_periode_masuk,
                    'nama_semester' => $semester['nama_semester'],
                    'id_status_mahasiswa' => $row['id_status_mahasiswa'],
                    'nama_status_mahasiswa' => $status_mahasiswa['nama_status_mahasiswa'],
                    'ips' => number_format($row['ips'], 2, '.', ','),
                    'ipk' => number_format($row['ipk'], 2, '.', ','),
                    'sks_semester' => $row['sks_semester'],
                    'sks_total' => $row['sks_total'],
                    'biaya_kuliah_smt' => number_format($row['biaya_kuliah_smt'], 2, '.', ','),
                    'id_pembiayaan' => $row['id_pembiayaan'],
                    'status_sync' => 'belum sync',
                ]);
            }

            DB::commit();
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();

            dd($th->getMessage());
        }
    }
}
