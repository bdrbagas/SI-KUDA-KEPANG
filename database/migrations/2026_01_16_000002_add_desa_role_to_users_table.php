<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('desa_id')->nullable()->after('id')->constrained('desas')->nullOnDelete();
            $table->enum('role', ['super_admin', 'admin_desa', 'admin_kecamatan', 'admin_puskesmas', 'admin_csr', 'viewer'])->default('viewer')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropColumn(['desa_id', 'role']);
        });
    }
};
