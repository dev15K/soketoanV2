<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhoPhuKien extends Model
{
    use HasFactory;

    public function phukien()
    {
        return $this->belongsTo(LoaiPhuKien::class, 'loai_phu_kien_id', 'id');
    }

    public function sanpham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id', 'id');
    }
}
