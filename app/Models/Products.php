<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }

    public function carts()
    {
        return $this->hasMany(Carts::class);
    }
}
