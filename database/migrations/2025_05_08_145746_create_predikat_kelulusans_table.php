<?php

use App\Models\Referensi\PredikatKelulusan;
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
        Schema::create('predikat_kelulusans', function (Blueprint $table) {
            $table->id();
            $table->string('indonesia')->nullable();
            $table->string('inggris')->nullable();
            $table->timestamps();
        });

        $data = [
            ['indonesia' => 'Memuaskan', 'inggris' => 'Very Satisfactory'],
            ['indonesia' => 'Sangat Memuaskan', 'inggris' => 'With Distinction'],
            ['indonesia' => 'Dengan Pujian', 'inggris' => 'Cumlaude'],
        ];

        foreach ($data as $item) {
            PredikatKelulusan::create($item);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predikat_kelulusans');
    }
};
