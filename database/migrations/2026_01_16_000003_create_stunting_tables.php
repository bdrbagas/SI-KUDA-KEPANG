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
        // Tabel Agregat Penduduk
        Schema::create('agregat_penduduks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->year('tahun');
            $table->integer('jumlah_penduduk')->default(0);
            $table->integer('jumlah_kk')->default(0);
            $table->integer('balita')->default(0);
            $table->integer('ibu_hamil')->default(0);
            $table->integer('laki_laki')->default(0);
            $table->integer('perempuan')->default(0);
            $table->timestamps();

            $table->unique(['desa_id', 'tahun']);
        });

        // Tabel Data Stunting
        Schema::create('data_stuntings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->date('periode');
            $table->integer('jumlah_balita_ditimbang')->default(0);
            $table->integer('jumlah_stunting')->default(0);
            $table->decimal('persentase_stunting', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel Ibu Hamil KEK (Kekurangan Energi Kronis)
        Schema::create('ibu_hamil_keks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->date('periode');
            $table->integer('jumlah_ibu_hamil')->default(0);
            $table->integer('jumlah_kek')->default(0);
            $table->decimal('persentase_kek', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel Anggaran Stunting
        Schema::create('anggaran_stuntings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->enum('sumber', ['desa', 'puskesmas', 'csr']);
            $table->year('tahun');
            $table->string('program');
            $table->text('deskripsi')->nullable();
            $table->decimal('pagu', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->decimal('persentase_realisasi', 5, 2)->default(0);
            $table->timestamps();
        });

        // Tabel By Name By Address (BNBA)
        Schema::create('bnba_stuntings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->string('nik', 20);
            $table->string('nama');
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat');
            $table->string('rt_rw', 20)->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->enum('status', ['stunting', 'kek', 'normal', 'resiko'])->default('normal');
            $table->string('posyandu')->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index(['desa_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bnba_stuntings');
        Schema::dropIfExists('anggaran_stuntings');
        Schema::dropIfExists('ibu_hamil_keks');
        Schema::dropIfExists('data_stuntings');
        Schema::dropIfExists('agregat_penduduks');
    }
};
