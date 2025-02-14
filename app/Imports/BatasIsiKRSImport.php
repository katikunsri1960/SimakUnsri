<?php

namespace App\Imports;

use App\Models\BatasIsiKRSManual;
use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\PembayaranManualMahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BatasIsiKRSImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '2048M');

        try {
            DB::beginTransaction();

            foreach ($collection as $index => $row) {
                // dd($row);
                $mahasiswa = RiwayatPendidikan::where('nim', $row['nim'])->orderBy('id_periode_masuk', 'desc')->first();

                // cek tanggal_pembayaran
                // jika format adalah dd-mm-yyyy ganti menjadi yyyy-mm-dd
                // jika format adalah yyyy-mm-dd tidak perlu diuba
                // tanggal_pembayaran adalah date format di excel
                $row['batas_isi_krs'] = date('Y-m-d', strtotime($row['batas_isi_krs']));

                if (!$mahasiswa) {
                    dd("Data mahasiswa dengan NIM {$row['nim']} tidak ditemukan!!");
                }

                BatasIsiKRSManual::updateOrCreate(['id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa, 'id_semester' => $row['id_semester']], [
                    'status_bayar' => $row['status_bayar'],
                    'batas_isi_krs' => $row['batas_isi_krs'],
                    'keterangan' => $row['keterangan'],
                    'nim' => $mahasiswa->nim,
                    'nama_mahasiswa' => $mahasiswa->nama_mahasiswa,
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
