<?php

use App\Enums\TrangThaiNguyenLieuThanhPham;
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
        Schema::create('nguyen_lieu_thanh_phams', function (Blueprint $table) {
            $table->id();
            $table->string('ten_san_pham')->nullable();
            $table->string('product_code')->unique();
            $table->timestamp('ngay')->default(Carbon::now())->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('nguyen_lieu_id');
//            $table->foreign('nguyen_lieu_san_xuat_id')->references('id')->on('nguyen_lieu_san_xuats')->onDelete('cascade');
            $table->float('khoi_luong')->comment('KL rieng gr')->default(0);
            $table->string('don_vi_tinh');
            $table->float('so_luong')->comment('Số lượng (cái/hộp)')->nullable();
            $table->decimal('price', 15, 0)->comment('Giá xuất kho thương mại')->default(0);
            $table->decimal('total_price', 15, 0)->comment('Tổng ')->default(0);
            $table->timestamp('ngay_san_xuat')->default(null)->nullable();
            $table->timestamp('han_su_dung')->default(null)->nullable();
            $table->longText('ghi_chu')->nullable();
            $table->string('trang_thai')->default(TrangThaiNguyenLieuThanhPham::ACTIVE());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieu_thanh_phams');
    }
};
