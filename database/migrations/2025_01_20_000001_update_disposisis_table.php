<?php
// database/migrations/2025_01_20_000001_update_disposisis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            // Persetujuan direktur/kabag
            $table->enum('keputusan', ['setuju', 'ditolak'])->nullable()->after('status');
            $table->text('catatan_penolakan')->nullable()->after('keputusan');
            $table->string('instruksi_disposisi')->nullable()->after('catatan_penolakan');

            // Untuk tracking bagian tujuan
            $table->foreignId('tujuan_bagian_id')->nullable()->after('kepada_user_id')
                  ->constrained('bagians')->nullOnDelete();
        });

        // Tambah status baru di surat_masuks
        // Kita perlu ubah enum status
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->enum('status', [
                'baru',
                'menunggu_direktur',
                'ditolak',
                'proses_disposisi',
                'selesai'
            ])->default('baru')->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            $table->dropForeign(['tujuan_bagian_id']);
            $table->dropColumn(['keputusan', 'catatan_penolakan', 'instruksi_disposisi', 'tujuan_bagian_id']);
        });

        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->enum('status', ['baru', 'proses_disposisi', 'selesai'])->default('baru')->after('keterangan');
        });
    }
};