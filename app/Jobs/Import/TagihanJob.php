<?php

namespace App\Jobs\Import;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\DB; // ✅ ini yang kurang
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TagihanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        DB::connection('keu_con')
            ->table('tagihan')
            ->whereBetween('id_record_tagihan', [$this->start, $this->end])
            ->orderBy('id_record_tagihan')
            // ->limit(500)
            ->chunkById(5000, function ($rows) {

                $data = [];

                foreach ($rows as $row) {

                    $data[] = [
                        'id_record_tagihan' => $row->id_record_tagihan,
                        'nomor_pembayaran' => $row->nomor_pembayaran,
                        'nama' => $row->nama,
                        'kode_fakultas' => $row->kode_fakultas,
                        'nama_fakultas' => $row->nama_fakultas,
                        'kode_prodi' => $row->kode_prodi,
                        'nama_prodi' => $row->nama_prodi,
                        'kode_periode' => $row->kode_periode,
                        'nama_periode' => $row->nama_periode,
                        'is_tagihan_aktif' => $row->is_tagihan_aktif,
                        'waktu_berlaku' => $this->safeDate($row->waktu_berlaku),
                        'waktu_berakhir' => $this->safeDate($row->waktu_berakhir),
                        'strata' => $row->strata,
                        'angkatan' => $row->angkatan,
                        'urutan_antrian' => $row->urutan_antrian,
                        'total_nilai_tagihan' => $row->total_nilai_tagihan,
                        'nomor_induk' => $row->nomor_induk,
                        'pembayaran_atau_voucher' => $row->pembayaran_atau_voucher,
                        'voucher_nama' => $row->voucher_nama,
                        'voucher_nama_fakultas' => $row->voucher_nama_fakultas,
                        'voucher_nama_prodi' => $row->voucher_nama_prodi,
                        'voucher_nama_periode' => $row->voucher_nama_periode,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('import_tagihan')->insertOrIgnore($data);

                $data = [];

            }, 'id_record_tagihan');
    }

    private function safeDate($value)
    {
        return ($value && $value !== '0000-00-00 00:00:00')
            ? $value
            : null;
    }
}