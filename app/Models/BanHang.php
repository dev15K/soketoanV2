<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanHang extends Model
{
    use HasFactory;

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id', 'id');
    }

    public function loaiQuy()
    {
        return $this->belongsTo(LoaiQuy::class, 'phuong_thuc_thanh_toan', 'id');
    }
}
