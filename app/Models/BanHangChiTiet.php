<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanHangChiTiet extends Model
{
    use HasFactory;

    protected $fillable = [
        'ban_hang_id',
        'san_pham_id',
        'gia_ban',
        'so_luong',
        'tong_tien',
    ];

    public function nguyenLieuTho()
    {
        return $this->belongsTo(NguyenLieuTho::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuPhanLoai()
    {
        return $this->belongsTo(NguyenLieuPhanLoai::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuTinh()
    {
        return $this->belongsTo(NguyenLieuTinh::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuSanXuat()
    {
        return $this->belongsTo(NguyenLieuSanXuat::class, 'san_pham_id', 'id');
    }

    public function nguyenLieuThanhpham()
    {
        return $this->belongsTo(NguyenLieuThanhPham::class, 'san_pham_id', 'id');
    }
}
