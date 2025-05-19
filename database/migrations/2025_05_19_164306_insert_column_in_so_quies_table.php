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
        Schema::table('so_quies', function (Blueprint $table) {
            $table->unsignedBigInteger('gia_tri_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('so_quies', function (Blueprint $table) {
            $table->dropColumn('gia_tri_id');
        });
    }
};
