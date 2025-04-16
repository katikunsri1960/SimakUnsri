<?php

namespace App\Imports;

use App\Models\Mahasiswa\RiwayatPendidikan;
use App\Models\PenundaanBayar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PenundaanBayarImport implements SkipsEmptyRows, ToCollection, WithHeadingRow
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

                if (! $mahasiswa) {
                    dd("Data mahasiswa dengan NIM {$row['nim']} tidak ditemukan!!");
                }

                PenundaanBayar::updateOrCreate(['id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa, 'id_semester' => $row['semester']], [
                    'status' => $row['status'],
                    'nim' => $mahasiswa->nim,
                    'keterangan' => $row['keterangan'] ?? null,
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
