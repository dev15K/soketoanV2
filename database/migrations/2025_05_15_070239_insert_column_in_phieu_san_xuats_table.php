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
        Schema::table('phieu_san_xuats', function (Blueprint $table) {
            $table->string('so_lo_san_xuat');
            $table->string('ghi_chu')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phieu_san_xuats', function (Blueprint $table) {
            $table->dropColumn('so_lo_san_xuat');
            $table->dropColumn('ghi_chu');
        });
    }
};
