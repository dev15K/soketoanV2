<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieuTinh extends Model
{
    use HasFactory;

    protected $fillable = [
        'ngay',
        'code',
        'trang_thai',
        'tong_khoi_luong',
        'gia_tien',
        'ten_nguyen_lieu',
        'ma_phieu',
        'so_luong_da_dung',
    ];
}
