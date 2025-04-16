<?php

namespace App\Imports;

use App\Models\ProgramStudi;
use App\Models\Referensi\PeriodePerkuliahan;
use App\Models\Semester;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PeriodeImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '2048M');

        try {
            DB::beginTransaction();
            foreach ($collection as $index => $row) {

                $prodi = ProgramStudi::where('kode_program_studi', $row['kode_prodi'])->where('status', 'A')->first();
                if (! $prodi) {
                    dd($row['kode_prodi']);
                }
                $semester = Semester::where('id_semester', $row['semester'])->first();
                // dd($row);
                $tanggal_awal_perkuliahan = date('Y-m-d', strtotime($row['tanggal_awal_perkuliahan']));
                $tanggal_akhir_perkuliahan = date('Y-m-d', strtotime($row['tanggal_akhir_perkuliahan']));

                PeriodePerkuliahan::updateOrCreate(['id_semester' => $row['semester'], 'id_prodi' => $prodi->id_prodi], [
                    'feeder' => 0,
                    'id_semester' => strval($row['semester']),
                    'nama_program_studi' => $prodi->nama_jenjang_pendidikan.' '.$prodi->nama_program_studi,
                    'nama_semester' => $semester->nama_semester,
                    'jumlah_target_mahasiswa_baru' => $row['jumlah_target_mahasiswa_baru'],
                    'jumlah_pendaftar_ikut_seleksi' => $row['jumlah_pendaftar_ikut_seleksi'],
                    'jumlah_pendaftar_lulus_seleksi' => $row['jumlah_pendaftar_lulus_seleksi'],
                    'jumlah_daftar_ulang' => $row['jumlah_daftar_ulang'],
                    'jumlah_mengundurkan_diri' => $row['jumlah_mengundurkan_diri'],
                    'tanggal_awal_perkuliahan' => $tanggal_awal_perkuliahan,
                    'tanggal_akhir_perkuliahan' => $tanggal_akhir_perkuliahan,
                    'jumlah_minggu_pertemuan' => $row['jumlah_minggu_pertemuan'],
                    'status_sync' => 'belum sync',
                ]);

            }

            DB::commit();

        } catch (\Throwable $th) {
            // throw $th;
            DB::rollBack();
            dd($th);
        }

    }
}
