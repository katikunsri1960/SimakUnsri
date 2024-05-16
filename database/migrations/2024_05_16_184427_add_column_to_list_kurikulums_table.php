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
        Schema::table('list_kurikulums', function (Blueprint $table) {
            $table->boolean('is_active')->default(0)->after('sk_kurikulum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_kurikulums', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
