<?php

use App\Models\Perkuliahan\NilaiPerkuliahan;
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
        Schema::table('nilai_perkuliahans', function (Blueprint $table) {
            $table->boolean('feeder')->default(1)->after('id');
        });

        NilaiPerkuliahan::where('id_semester', '20241')->update(['feeder' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_perkuliahans', function (Blueprint $table) {
            $table->dropColumn('feeder');
        });
    }
};
