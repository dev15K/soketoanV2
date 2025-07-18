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
        Schema::table('nguyen_lieu_phan_loais', function (Blueprint $table) {
            $table->string('tam_nhanh_sao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguyen_lieu_phan_loais', function (Blueprint $table) {
            $table->dropColumn('tam_nhanh_sao');
        });
    }
};
