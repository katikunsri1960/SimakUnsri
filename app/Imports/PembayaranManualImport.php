<?php

namespace App\Imports;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\PembayaranManualMahasiswa;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PembayaranManualImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
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

                // cek tanggal_pembayaran
                // jika format adalah dd-mm-yyyy ganti menjadi yyyy-mm-dd
                // jika format adalah yyyy-mm-dd tidak perlu diuba
                // tanggal_pembayaran adalah date format di excel
                $row['tanggal_pembayaran'] = date('Y-m-d', strtotime($row['tanggal_pembayaran']));

                if (! $mahasiswa) {
                    return back()->withErrors(["Data mahasiswa dengan NIM {$row['nim']} tidak ditemukan!!"]);
                }

                PembayaranManualMahasiswa::updateOrCreate(['id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa, 'id_semester' => $row['id_semester']], [
                    'status' => $row['status'],
                    'tanggal_pembayaran' => $row['tanggal_pembayaran'],
                    'nominal_ukt' => $row['nominal_ukt'],
                    'nim' => $mahasiswa->nim,
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
