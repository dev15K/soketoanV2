<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orders()
    {
        return $this->belongsTo(Orders::class, 'order_id', 'id');
    }

    public function products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'id');
    }
}
