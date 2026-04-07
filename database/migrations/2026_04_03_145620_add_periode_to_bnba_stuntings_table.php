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
        Schema::table('bnba_stuntings', function (Blueprint $table) {
            $table->date('periode')->nullable()->after('desa_id');
        });

        // Backfill data lama menggunakan created_at atau awal tahun 2024 jika null
        \Illuminate\Support\Facades\DB::statement("UPDATE bnba_stuntings SET periode = DATE(created_at) WHERE created_at IS NOT NULL");
        \Illuminate\Support\Facades\DB::statement("UPDATE bnba_stuntings SET periode = '2024-01-01' WHERE periode IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bnba_stuntings', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
    }
};
