<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('data_wisuda', function (Blueprint $table) {

            // Tambah kolom sebelum no_sk_yudisium
            $table->unsignedBigInteger('id_sk_yudisium')
                ->nullable()
                ->before('no_sk_yudisium')
                ->after('no_sk_yudisium');

            // Tambah foreign key ke tabel file_fakultas
            $table->foreign('id_sk_yudisium')
                ->references('id')
                ->on('file_fakultas')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('data_wisuda', function (Blueprint $table) {
            $table->dropForeign(['id_sk_yudisium']);
            $table->dropColumn('id_sk_yudisium');
        });
    }
};
