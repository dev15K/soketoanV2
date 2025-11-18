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
        Schema::create('lich_su_kho_phu_kiens', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('loai_phu_kien_id');
            $table->foreign('loai_phu_kien_id')->references('id')->on('kho_phu_kiens')->onDelete('cascade');

            $table->string('so_luong');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_kho_phu_kiens');
    }
};
