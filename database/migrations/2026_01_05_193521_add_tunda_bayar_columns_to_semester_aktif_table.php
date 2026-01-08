<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->date('mulai_tunda_bayar')
                ->nullable()
                ->after('batas_bayar_ukt');

            $table->date('batas_tunda_bayar')
                ->nullable()
                ->after('mulai_tunda_bayar');
        });
    }

    public function down(): void
    {
        Schema::table('semester_aktifs', function (Blueprint $table) {
            $table->dropColumn([
                'mulai_tunda_bayar',
                'batas_tunda_bayar'
            ]);
        });
    }
};
