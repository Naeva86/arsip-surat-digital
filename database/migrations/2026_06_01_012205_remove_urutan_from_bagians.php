<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bagians', function (Blueprint $table) {
            if (Schema::hasColumn('bagians', 'urutan')) {
                $table->dropColumn('urutan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bagians', function (Blueprint $table) {
            $table->integer('urutan')->default(0)->after('parent_id');
        });
    }
};