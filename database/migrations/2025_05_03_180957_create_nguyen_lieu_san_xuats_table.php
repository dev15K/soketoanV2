<?php

use App\Enums\TrangThaiNguyenLieuSanXuat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nguyen_lieu_san_xuats', function (Blueprint $table) {
            $table->id();

            $table->timestamp('ngay')->default(Carbon::now())->nullable();

            $table->string('code')->unique();

            $table->string('trang_thai')->default(TrangThaiNguyenLieuSanXuat::ACTIVE());

            $table->float('tong_khoi_luong')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieu_san_xuats');
    }
};
