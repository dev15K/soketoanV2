<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('phieu_san_xuats', function (Blueprint $table) {
            $table->unsignedBigInteger('nhan_su_xu_li_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('phieu_san_xuats', function (Blueprint $table) {
            $table->dropColumn('nhan_su_xu_li_id');
        });
    }
};
