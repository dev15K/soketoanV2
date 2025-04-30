<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Products::class);
    }

    public function news()
    {
        return $this->hasMany(News::class);
    }

}
