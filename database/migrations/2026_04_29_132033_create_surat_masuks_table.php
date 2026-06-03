<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('no_agenda')->unique();
            $table->string('nomor_surat');
            $table->string('judul_surat');
            $table->string('pengirim');
            $table->date('tanggal_surat');
            $table->date('tanggal_arsip');
            $table->string('diagendakan_nomor')->nullable();
            $table->enum('sifat', ['biasa', 'penting', 'rahasia', 'urgent'])->default('biasa');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->string('file_path')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['baru', 'proses_disposisi', 'selesai'])->default('baru');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kategori_id')
                  ->references('id')->on('kategori_surats')
                  ->nullOnDelete();

            $table->foreign('user_id')
                  ->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};