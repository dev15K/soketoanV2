<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhieuSanXuat extends Model
{
    use HasFactory;

    public function nguyenLieuTinh()
    {
        return $this->belongsTo(NguyenLieuTinh::class, 'nguyen_lieu_id', 'id');
    }

    public function nhan_su_xu_li()
    {
        return $this->belongsTo(User::class, 'nhan_su_xu_li_id', 'id');
    }
}
