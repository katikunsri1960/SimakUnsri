<?php

use App\Models\JalurMasuk;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $data = [
            [
                'id_jalur_masuk' => 1,
                'nama_jalur_masuk' => 'SBMPTN',
            ],
            [
                'id_jalur_masuk' => 2,
                'nama_jalur_masuk' => 'SNMPTN',
            ],
            [
                'id_jalur_masuk' => 5,
                'nama_jalur_masuk' => 'Seleksi Mandiri PTN',
            ],
            [
                'id_jalur_masuk' => 6,
                'nama_jalur_masuk' => 'Seleksi Mandiri PTS',
            ],
            [
                'id_jalur_masuk' => 7,
                'nama_jalur_masuk' => 'Ujian Masuk Bersama PTN (UMB-PT)',
            ],
            [
                'id_jalur_masuk' => 8,
                'nama_jalur_masuk' => 'Ujian Masuk Bersama PTS (UMB-PTS)',
            ],
        ];

        foreach ($data as $d) {
            JalurMasuk::updateOrCreate(['id_jalur_masuk' => $d['id_jalur_masuk']], $d);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }

};
