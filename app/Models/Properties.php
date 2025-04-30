<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Properties extends Model
{
    use HasFactory;

    public function attribute()
    {
        return $this->belongsTo(Attributes::class, 'attribute_id', 'id');
    }
}
