<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NoUrutWisudaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $updatedCount = 0;
        $failedRows = [];

        DB::transaction(function () use ($rows, &$updatedCount, &$failedRows) {
            foreach ($rows as $index => $row) {
                // Read row keys
                $noUrut   = isset($row['no_urut']) && is_numeric($row['no_urut']) ? (int) $row['no_urut'] : null;
                $wisudaKe = isset($row['wisuda_ke']) && is_numeric($row['wisuda_ke']) ? (int) $row['wisuda_ke'] : null;
                $nimRaw   = isset($row['nim']) ? trim((string) $row['nim']) : null;

                if ($noUrut === null || $wisudaKe === null || empty($nimRaw)) {
                    continue;
                }

                // Variasi NIM untuk antisipasi hilangnya angka 0 di depan oleh Excel
                $nimPadded = str_pad($nimRaw, 14, '0', STR_PAD_LEFT);

                // Execute Query Update (Cek NIM Asli ATAU NIM dengan Leading Zero)
                $affected = DB::table('data_wisuda')
                    ->join('riwayat_pendidikans', 'riwayat_pendidikans.id_registrasi_mahasiswa', '=', 'data_wisuda.id_registrasi_mahasiswa')
                    ->where(function ($q) use ($nimRaw, $nimPadded) {
                        $q->where('riwayat_pendidikans.nim', $nimRaw)
                          ->orWhere('riwayat_pendidikans.nim', $nimPadded)
                          ->orWhere('riwayat_pendidikans.nim', 'LIKE', '%' . $nimRaw);
                    })
                    ->where('data_wisuda.wisuda_ke', $wisudaKe)
                    ->update([
                        'data_wisuda.no_urut'    => $noUrut,
                        'data_wisuda.updated_at' => now()
                    ]);

                if ($affected > 0) {
                    $updatedCount++;
                } else {
                    $failedRows[] = "Baris " . ($index + 2) . " -> NIM Excel: {$nimRaw} (Padded: {$nimPadded}) | Wisuda Ke: {$wisudaKe}";
                }
            }
        });

        Log::info("IMPORT NO URUT RESULT: Total Updated = {$updatedCount}", [
            'gagal_rows' => $failedRows
        ]);

        if ($updatedCount === 0) {
            $detailGagal = !empty($failedRows) ? implode(', ', $failedRows) : 'Data kosong';
            throw new \Exception("Gagal Update! Tidak ada NIM di Excel yang cocok dengan Periode Wisuda di Database. Detail: [{$detailGagal}]");
        }
    }
}