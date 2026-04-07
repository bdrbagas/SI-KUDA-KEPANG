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
        // Tabel Dokumentasi Kegiatan Lingkungan
        Schema::create('dokumentasi_lingkungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('jenis_kegiatan', [
                'penanaman_pohon',
                'pembersihan_sampah',
                'penebangan_liar'
            ]);
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_kegiatan');
            $table->string('lokasi');
            $table->integer('jumlah_peserta')->default(0);
            $table->string('penanggung_jawab')->nullable();
            $table->json('foto')->nullable()->comment('Array path foto');
            $table->text('hasil_kegiatan')->nullable();
            $table->timestamps();

            $table->index(['desa_id', 'jenis_kegiatan']);
            $table->index('tanggal_kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumentasi_lingkungans');
    }
};
