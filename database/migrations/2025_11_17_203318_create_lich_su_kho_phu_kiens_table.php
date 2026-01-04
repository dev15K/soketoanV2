<?php

use Carbon\Carbon;
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

            $table->unsignedBigInteger('kho_phu_kien_id');
            $table->string('so_luong');
            $table->timestamp('ngay_nhap')->nullable()->default(Carbon::now());
            $table->text('ghi_chu')->nullable();

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
