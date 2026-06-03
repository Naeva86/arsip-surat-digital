<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('disposisi_logs', function (Blueprint $table) {
            $table->string('status_lama')->nullable()->change();
            $table->string('status_baru')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('disposisi_logs', function (Blueprint $table) {
            $table->string('status_lama')->nullable(false)->change();
            $table->string('status_baru')->nullable(false)->change();
        });
    }
};