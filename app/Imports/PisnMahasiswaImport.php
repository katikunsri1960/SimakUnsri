<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Mahasiswa\PisnMahasiswa;
use App\Models\SemesterAktif;
use App\Models\PeriodeWisuda;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PisnMahasiswaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $collection)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '2048M');

        DB::beginTransaction();

        try {
            foreach ($collection as $row) {

                // 🔎 Validasi kolom wajib
                if (!isset($row['nim'])) {
                    throw new \Exception('Kolom NIM tidak ditemukan di file Excel');
                }

                $mahasiswa = LulusDo::where('nim', $row['nim'])
                    ->orderBy('angkatan', 'desc')
                    ->first();

                if (!$mahasiswa) {
                    throw new \Exception("Mahasiswa dengan NIM {$row['nim']} tidak ditemukan");
                }

                $periode = PeriodeWisuda::where('periode', $row['periode_wisuda'])
                    ->where('is_active', 1)
                    ->first();

                if (!$periode) {
                    throw new \Exception("Periode wisuda {$row['periode_wisuda']} tidak aktif");
                }

                PisnMahasiswa::updateOrCreate(
                    [
                        'id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa,
                        'id_semester'            => $row['id_semester'],
                        'periode_wisuda'         => $row['periode_wisuda'],
                    ],
                    [
                        'nim' => $mahasiswa->nim,
                        'penomoran_ijazah_nasional' => $row['penomoran_ijazah_nasional'],
                    ]
                );
            }

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e; // biar muncul di controller
        }
    }
}