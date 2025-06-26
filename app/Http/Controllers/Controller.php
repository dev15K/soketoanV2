<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private function deleteIfZero($model, $field, $list_id, $trang_thai)
    {
        $model::whereIn('id', $list_id)
            ->where(function ($query) use ($field) {
                $query->whereNull($field)
                    ->orWhere($field, '<=', 0.00001);
            })
            ->update(['trang_thai' => $trang_thai]);
    }
}
