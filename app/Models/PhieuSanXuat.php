<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhieuSanXuat extends Model
{
    use HasFactory;

    protected $fillable = [
        'ten_phieu',
        'ngay',
        'code',
        'trang_thai',
        'tong_khoi_luong',
        'so_lo_san_xuat',
        'ghi_chu',
        'nguyen_lieu_id',
        'nhan_su_xu_li_id',
        'khoi_luong_da_dung',
        'thoi_gian_hoan_thanh_san_xuat',
    ];

    public function nguyenLieuTinh()
    {
        return $this->belongsTo(NguyenLieuTinh::class, 'nguyen_lieu_id', 'id');
    }

    public function nhan_su_xu_li()
    {
        return $this->belongsTo(User::class, 'nhan_su_xu_li_id', 'id');
    }
}
