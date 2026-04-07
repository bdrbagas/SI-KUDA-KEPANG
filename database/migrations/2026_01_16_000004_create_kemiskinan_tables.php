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
        // Tabel Desil Kemiskinan
        Schema::create('desil_kemiskinans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->year('tahun');
            $table->integer('desil_1')->default(0)->comment('Sangat Miskin');
            $table->integer('desil_2')->default(0)->comment('Miskin');
            $table->integer('desil_3')->default(0)->comment('Hampir Miskin');
            $table->integer('desil_4')->default(0)->comment('Rentan Miskin');
            $table->integer('desil_5')->default(0);
            $table->integer('total_keluarga')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['desa_id', 'tahun']);
        });

        // Tabel Data Pengangguran
        Schema::create('data_penganggurans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->year('tahun');
            $table->integer('jumlah_pengangguran')->default(0);
            $table->integer('usia_produktif')->default(0);
            $table->integer('angkatan_kerja')->default(0);
            $table->decimal('tingkat_pengangguran', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['desa_id', 'tahun']);
        });

        // Tabel Realisasi PKH (Program Keluarga Harapan)
        Schema::create('realisasi_pkhs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->year('tahun');
            $table->integer('jumlah_kpm')->default(0)->comment('Keluarga Penerima Manfaat');
            $table->decimal('anggaran', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->decimal('persentase_realisasi', 5, 2)->default(0);
            $table->string('periode_penyaluran')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel Realisasi Sembako/BPNT
        Schema::create('realisasi_sembakos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->year('tahun');
            $table->string('bulan', 20)->nullable();
            $table->integer('jumlah_kpm')->default(0);
            $table->decimal('anggaran', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel Data AK-1 (Pencari Kerja)
        Schema::create('data_ak1s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->year('tahun');
            $table->integer('total_pencaker')->default(0)->comment('Pencari Kerja');
            $table->integer('laki_laki')->default(0);
            $table->integer('perempuan')->default(0);
            $table->integer('sd_sederajat')->default(0);
            $table->integer('smp_sederajat')->default(0);
            $table->integer('sma_sederajat')->default(0);
            $table->integer('diploma')->default(0);
            $table->integer('sarjana')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['desa_id', 'tahun']);
        });

        // Tabel Data Ojek Pangkalan
        Schema::create('data_ojengs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->string('nama_pangkalan');
            $table->string('lokasi');
            $table->integer('jumlah_ojek')->default(0);
            $table->string('koordinator')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_ojengs');
        Schema::dropIfExists('data_ak1s');
        Schema::dropIfExists('realisasi_sembakos');
        Schema::dropIfExists('realisasi_pkhs');
        Schema::dropIfExists('data_penganggurans');
        Schema::dropIfExists('desil_kemiskinans');
    }
};
