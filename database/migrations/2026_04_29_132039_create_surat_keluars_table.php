<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->string('judul_surat');
            $table->string('penerima');
            $table->date('tanggal_surat');
            $table->date('tanggal_arsip');
            $table->enum('sifat', ['biasa', 'penting', 'rahasia'])->default('biasa');
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->string('file_path')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('bagian_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kategori_id')
                  ->references('id')->on('kategori_surats')
                  ->nullOnDelete();

            $table->foreign('user_id')
                  ->references('id')->on('users');

            $table->foreign('bagian_id')
                  ->references('id')->on('bagians')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};