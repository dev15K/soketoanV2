<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiQuy;
use App\Models\SoQuy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
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
            ->orderByDesc('id')
            ->get();

        $old_query = SoQuy::whereNull('deleted_at');

        if ($start_date) {
            $old_query->whereDate('created_at', '<', $start_date);
        } elseif ($end_date) {
            $old_query->whereDate('created_at', '<', $end_date);
        } else {
            $old_query->whereDate('created_at', '<', Carbon::today());
        }

        $old_datas = $old_query->orderByDesc('id')->get();

        $ton_dau = 0;
        foreach ($old_datas as $old_data) {
            if ($old_data->loai == 1) {
                $ton_dau += $old_data->so_tien;
            } else {
                $ton_dau -= $old_data->so_tien;
            }
        }

        $query = SoQuy::whereNull('deleted_at');

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        } elseif ($start_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($start_date)->endOfDay()
            ]);
        } elseif ($end_date) {
            $query->whereBetween('created_at', [
                Carbon::today(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        } else {
            $query->whereBetween('created_at', [
                Carbon::today(),
                Carbon::today()->endOfDay()
            ]);
        }

        $new_datas = $query->orderByDesc('id')->get();

        $ton_cuoi = 0;
        foreach ($new_datas as $new_data) {
            if ($new_data->loai == 1) {
                $ton_cuoi += $new_data->so_tien;
            } else {
                $ton_cuoi -= $new_data->so_tien;
            }
        }

        $ma_phieu = $this->generateCode();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.index', compact('datas', 'ton_dau', 'ton_cuoi', 'ma_phieu', 'start_date', 'end_date', 'loai_quies', 'loai_quy_search'));
    }

    private function generateCode()
    {
        $lastItem = SoQuy::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }
}
