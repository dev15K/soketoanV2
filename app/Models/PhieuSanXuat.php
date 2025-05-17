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
}
