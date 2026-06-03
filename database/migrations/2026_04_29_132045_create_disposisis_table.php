<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surat_masuk_id');
            $table->unsignedBigInteger('dari_user_id');
            $table->unsignedBigInteger('kepada_user_id');
            $table->integer('level')->default(1);
            $table->text('isi_disposisi');
            $table->enum('status', [
                'menunggu',
                'dibaca',
                'diproses',
                'diteruskan',
                'selesai'
            ])->default('menunggu');
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamp('diproses_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->text('catatan_tindak_lanjut')->nullable();
            $table->timestamps();

            $table->foreign('surat_masuk_id')
                  ->references('id')->on('surat_masuks')
                  ->cascadeOnDelete();

            $table->foreign('dari_user_id')
                  ->references('id')->on('users');

            $table->foreign('kepada_user_id')
                  ->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }

};