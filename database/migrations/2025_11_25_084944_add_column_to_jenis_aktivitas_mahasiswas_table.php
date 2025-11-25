<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('jenis_aktivitas_mahasiswas', function (Blueprint $table) {
            if (!Schema::hasColumn('jenis_aktivitas_mahasiswas', 'jenis_aktivitas_mahasiswa')) {
                $table->string('jenis_aktivitas_mahasiswa')->nullable()->after('id_jenis_aktivitas_mahasiswa');
            }
        });
    }

    public function down()
    {
        Schema::table('jenis_aktivitas_mahasiswas', function (Blueprint $table) {
            $table->dropColumn('jenis_aktivitas_mahasiswa');
        });
    }
};
