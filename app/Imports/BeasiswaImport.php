<?php

namespace App\Imports;

use App\Models\BeasiswaMahasiswa;
use App\Models\Mahasiswa\RiwayatPendidikan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BeasiswaImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '2048M');

        try {
            DB::beginTransaction();

            foreach ($collection as $index => $row) {

                $mahasiswa = RiwayatPendidikan::where('nim', $row['nim'])->orderBy('id_periode_masuk', 'desc')->first();

                // cek tanggal mulai dan akhir beasiswa
                // jika format adalah dd-mm-yyyy ganti menjadi yyyy-mm-dd
                // jika format adalah yyyy-mm-dd tidak perlu diuba
                // tanggal mulai adalah date format di excel
                // tanggal akhir adalah date format di excel
                $row['tanggal_mulai_beasiswa'] = date('Y-m-d', strtotime($row['tanggal_mulai_beasiswa']));
                $row['tanggal_akhir_beasiswa'] = date('Y-m-d', strtotime($row['tanggal_akhir_beasiswa']));

                if (! $mahasiswa) {
                    dd("Data mahasiswa dengan NIM {$row['nim']} tidak ditemukan!!");
                }

                BeasiswaMahasiswa::updateOrCreate(['id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa],
                    [
                        'id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa,
                        'nim' => $mahasiswa->nim,
                        'nama_mahasiswa' => $mahasiswa->nama_mahasiswa,
                        'id_jenis_beasiswa' => $row['jenis_beasiswa'],
                        'id_pembiayaan' => $row['pembiayaan'],
                        'tanggal_mulai_beasiswa' => $row['tanggal_mulai_beasiswa'],
                        'tanggal_akhir_beasiswa' => $row['tanggal_akhir_beasiswa'],
                        'link_sk' => $row['link_sk'],
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
