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
        Schema::table('lich_su_kho_phu_kiens', function (Blueprint $table) {
            $table->unsignedBigInteger('kho_phu_kien_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lich_su_kho_phu_kiens', function (Blueprint $table) {
            $table->dropColumn('kho_phu_kien_id');
        });
    }
};
