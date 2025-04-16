<?php

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
        Schema::create('pejabat_universitas_jabatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        $data = [
            'Rektor',
            'Wakil Rektor Bidang Akademik',
            'Wakil Rektor Bidang Umum, Kepegawaian dan Keuangan',
            'Wakil Rektor Bidang Kemahasiswaan dan Alumni',
            'Wakil Rektor Bidang Perencanaan dan Kerjasama',
        ];

        foreach ($data as $d) {
            \App\Models\Referensi\PejabatUniversitasJabatan::create([
                'nama' => $d,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pejabat_universitas_jabatans');
    }
};
