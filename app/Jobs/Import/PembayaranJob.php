<?php

namespace App\Jobs\Import;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\DB; // ✅ ini yang kurang
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PembayaranJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function handle()
    {
        DB::connection('keu_con')
            ->table('pembayaran')
            ->whereBetween('id_record_pembayaran', [$this->start, $this->end])
            ->orderBy('id_record_pembayaran')
            ->chunkById(2000, function ($rows) {

                $data = [];

                foreach ($rows as $row) {
                    $data[] = [
                        'id_record_pembayaran' => $row->id_record_pembayaran,
                        'id_record_tagihan' => $row->id_record_tagihan,
                        'waktu_transaksi' => $this->safeDate($row->waktu_transaksi),
                        'nomor_pembayaran' => $row->nomor_pembayaran,
                        'total_nilai_pembayaran' => $row->total_nilai_pembayaran,
                        'status_pembayaran' => $row->status_pembayaran,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('import_pembayaran')->upsert(
                    $data,
                    ['id_record_pembayaran']
                );

            }, 'id_record_pembayaran');
    }

    private function safeDate($value)
    {
        return ($value && $value !== '0000-00-00 00:00:00')
            ? $value
            : null;
    }
}
