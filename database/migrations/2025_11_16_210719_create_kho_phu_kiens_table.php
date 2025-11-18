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
        Schema::create('kho_phu_kiens', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('loai_phu_kien_id');
            $table->foreign('loai_phu_kien_id')->references('id')->on('loai_phu_kiens')->onDelete('cascade');

            $table->unsignedBigInteger('san_pham_id');

            $table->string('so_luong');
            $table->string('so_luong_da_ban');

            $table->softDeletes('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kho_phu_kiens');
    }
};
