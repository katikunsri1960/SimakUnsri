<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use App\Models\Mahasiswa\LulusDo;
use App\Models\Mahasiswa\PisnMahasiswa;
use App\Models\SemesterAktif;
use App\Models\PeriodeWisuda;
use Maatwebsite\Excel\Concerns\ToCollection;

class PisnMahasiswaImport implements ToCollection
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
                $mahasiswa = LulusDo::where('nim', $row['nim'])->orderBy('id_periode_masuk', 'desc')->first();

                if (!$mahasiswa) {
                    return back()->withErrors(["Data mahasiswa dengan NIM {$row['nim']} tidak ditemukan!!"]);
                }

                $periode = PeriodeWisuda::where('periode', $row['periode_wisuda'])->where('is_active', 1)->first();

                if (!$periode) {
                    return back()->withErrors(["Periode wisuda {$row['nim']} tidak aktif!!"]);
                }

                PisnMahasiswa::updateOrCreate(['id_registrasi_mahasiswa' => $mahasiswa->id_registrasi_mahasiswa, 'id_semester' => $row['id_semester'], 'periode_wisuda' => $row['periode_wisuda']], [
                    'nim' => $$mahasiswa->nim,
                    'penomoran_ijazah_nasional' => $row['penomoran_ijazah_nasional']
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
