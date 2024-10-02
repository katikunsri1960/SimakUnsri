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
        Schema::table('anggota_aktivitas_mahasiswas', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('uji_mahasiswas', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('nilai_transfer_pendidikans', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('konversi_aktivitas', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('revisi_sidang_mahasiswa', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('notulensi_sidang_mahasiswa', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('nilai_sidang_mahasiswa', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggota_aktivitas_mahasiswas', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
        });

        Schema::table('uji_mahasiswas', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
        });

        Schema::table('nilai_transfer_pendidikans', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
        });

        Schema::table('konversi_aktivitas', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
        });

        Schema::table('revisi_sidang_mahasiswa', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
        });

        Schema::table('notulensi_sidang_mahasiswa', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
        });

        Schema::table('nilai_sidang_mahasiswa', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['id_aktivitas']);

            // Add the new foreign key constraint with onUpdate cascade
            $table->foreign('id_aktivitas')->references('id_aktivitas')->on('aktivitas_mahasiswas')->onDelete('cascade');
        });
    }
};
