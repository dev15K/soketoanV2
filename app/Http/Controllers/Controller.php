<?php

namespace App\Http\Controllers;

use App\Models\LoaiQuy;
use App\Models\NhomQuy;
use App\Models\SoQuy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function get_data_so_quy_index(Request $request, $view_prefix = '')
    {
        $start_date = $request->input('start_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $end_date   = $request->input('end_date') ?? Carbon::now()->toDateString();
        $loai_quy_search = $request->input('loai_quy_search');

        $datas = SoQuy::where('deleted_at', null)
            ->when($start_date, function ($query) use ($start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->when($loai_quy_search, function ($query) use ($loai_quy_search) {
                return $query->where('loai_quy_id', $loai_quy_search);
            })
            ->where('so_tien', '>', 0)
            ->orderBy('id', 'desc')
            ->get();

        $ton_dau = get_ton_dau($start_date, $end_date);
        $ton_cuoi = get_ton_cuoi($start_date, $end_date);
        $thu = get_thu($start_date, $end_date);
        $chi = get_chi($start_date, $end_date);

        $ma_phieu = $this->generateCode();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();

        $nhom_quies = NhomQuy::orderByDesc('id')->get();
        return view($view_prefix, compact('datas', 'nhom_quies', 'ton_dau', 'ton_cuoi', 'ma_phieu', 'thu', 'chi',
            'start_date', 'end_date', 'loai_quies', 'loai_quy_search'));
    }

    private function generateCode()
    {
        $lastItem = SoQuy::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    private function deleteIfZero($model, $field, $list_id, $trang_thai)
    {
        $model::whereIn('id', $list_id)
            ->where(function ($query) use ($field) {
                $query->whereNull($field)
                    ->orWhere($field, ' <= ', 0.00001);
            })
            ->update(['trang_thai' => $trang_thai]);
    }
}
