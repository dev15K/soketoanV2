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
        Schema::create('loai_phu_kiens', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phu_kien');
            $table->string('ten_phu_kien');
            $table->string('don_vi_tinh');
            $table->string('mo_ta_phu_kien');
            $table->softDeletes('deleted_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_phu_kiens');
    }
};
