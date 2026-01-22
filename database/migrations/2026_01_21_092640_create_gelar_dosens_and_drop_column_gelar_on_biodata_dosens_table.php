<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1️⃣ Hapus kolom lama
        Schema::table('biodata_dosens', function (Blueprint $table) {
            if (
                Schema::hasColumn('biodata_dosens', 'gelar_depan') &&
                Schema::hasColumn('biodata_dosens', 'gelar_belakang')
            ) {
                $table->dropColumn(['gelar_depan', 'gelar_belakang']);
            }
        });

        // 2️⃣ Buat tabel gelar_dosens
        Schema::create('gelar_dosens', function (Blueprint $table) {
            $table->id();

            $table->string('id_dosen'); // FK ke biodata_dosens.id_dosen

            $table->string('gelar_depan_s1', 50)->nullable();
            $table->string('gelar_depan_s2', 50)->nullable();
            $table->string('gelar_depan_s3', 50)->nullable();
            $table->string('gelar_depan_gb', 50)->nullable();
            $table->string('gelar_belakang_s1', 50)->nullable();
            $table->string('gelar_belakang_s2', 50)->nullable();
            $table->string('gelar_belakang_s3', 50)->nullable();

            $table->timestamps();

            $table->foreign('id_dosen')
                ->references('id_dosen')
                ->on('biodata_dosens')
                ->cascadeOnDelete();

            $table->unique('id_dosen'); // ⬅️ PENTING (1 dosen 1 data gelar)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gelar_dosens');

        Schema::table('biodata_dosens', function (Blueprint $table) {
            $table->string('gelar_depan', 50)->nullable()->after('nama_dosen');
            $table->string('gelar_belakang', 50)->nullable()->after('gelar_depan');
        });
    }
};
