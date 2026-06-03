<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisi_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('disposisi_id');
            $table->unsignedBigInteger('user_id');
            $table->string('status_lama');
            $table->string('status_baru');
            $table->text('catatan')->nullable();
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();

            $table->foreign('disposisi_id')
                  ->references('id')->on('disposisis')
                  ->cascadeOnDelete();

            $table->foreign('user_id')
                  ->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisi_logs');
    }
};