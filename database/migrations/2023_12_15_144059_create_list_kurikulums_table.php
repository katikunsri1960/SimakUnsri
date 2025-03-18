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
        Schema::create('list_kurikulums', function (Blueprint $table) {
            $table->id();
            $table->integer("id_jenj_didik")->nullable();
            $table->integer("jml_sem_normal")->nullable();
            $table->string("id_kurikulum")->unique();
            // $table->index("id_kurikulum");
            $table->string("nama_kurikulum");
            $table->string("id_prodi");
            $table->string("nama_program_studi");
            $table->string("id_semester");
            $table->string("semester_mulai_berlaku");
            $table->integer("jumlah_sks_lulus");
            $table->integer("jumlah_sks_wajib");
            $table->integer("jumlah_sks_pilihan");
            $table->float("jumlah_sks_mata_kuliah_wajib")->nullable();
            $table->float("jumlah_sks_mata_kuliah_pilihan")->nullable();
            $table->string("status_sync");
            $table->string("sk_kurikulum")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_kurikulums');
    }
};
