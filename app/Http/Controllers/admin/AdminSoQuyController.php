<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNhaCungCap;
use App\Http\Controllers\Controller;
use App\Models\LoaiQuy;
use App\Models\NhaCungCaps;
use App\Models\SoQuy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminSoQuyController extends Controller
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
            ->paginate(10);

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

        $nha_cung_caps = NhaCungCaps::where('trang_thai', '!=', TrangThaiNhaCungCap::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.so_quy.index', compact('datas', 'ma_phieu', 'ton_dau', 'ton_cuoi',
            'start_date', 'end_date', 'loai_quies', 'loai_quy_search', 'nha_cung_caps'));
    }

    private function generateCode()
    {
        $lastItem = SoQuy::orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    public function detail($id)
    {
        $soquy = SoQuy::find($id);
        if (!$soquy || $soquy->deleted_at != null) {
            return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
        }
        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.so_quy.detail', compact('soquy', 'loai_quies'));
    }

    public function store(Request $request)
    {
        try {
            $loai = $request->input('loai');
            $so_tien = $request->input('so_tien');
            $noi_dung = $request->input('noi_dung');
            $ngay = $request->input('ngay');
            $loai_quy_id = $request->input('loai_quy_id');

            $soquy = new SoQuy();

            $ma_phieu = $request->input('ma_phieu');

            $soquy->ma_phieu = $ma_phieu;
            $soquy->loai = $loai;
            $soquy->so_tien = $so_tien;
            $soquy->noi_dung = $noi_dung;
            $soquy->ngay = $ngay;
            $soquy->loai_quy_id = $loai_quy_id;

            $soquy->save();

            $loai_quy = LoaiQuy::find($loai_quy_id);
            if ($loai_quy) {
                if ($loai == 1) {
                    $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy + $so_tien;
                    $loai_quy->save();
                } else {
                    $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $so_tien;
                    $loai_quy->save();
                }
            }

//            if ($loai == 1) {
//
//            }

            return redirect()->back()->with('success', 'Thêm mới sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $loai = $request->input('loai');
            $so_tien = $request->input('so_tien');
            $noi_dung = $request->input('noi_dung');
            $ngay = $request->input('ngay');
            $loai_quy_id = $request->input('loai_quy_id');

            $soquy = SoQuy::find($id);
            if (!$soquy || $soquy->deleted_at != null) {
                return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
            }

            $old_id = $soquy->loai_quy_id;
            $old_tien = $soquy->so_tien;

            $soquy->loai = $loai;
            $soquy->so_tien = $so_tien;
            $soquy->noi_dung = $noi_dung;
            $soquy->ngay = $ngay;
            $soquy->loai_quy_id = $loai_quy_id;
            $soquy->save();

            if ($old_id != $loai_quy_id) {
                if ($loai == 1) {
                    $loai_quy = LoaiQuy::find($old_id);
                    if ($loai_quy) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $old_tien;
                        $loai_quy->save();
                    }

                    $loai_quy = LoaiQuy::find($loai_quy_id);
                    if ($loai_quy) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy + $so_tien;
                        $loai_quy->save();
                    }
                } else {
                    $loai_quy = LoaiQuy::find($old_id);
                    if ($loai_quy) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy + $old_tien;
                        $loai_quy->save();
                    }

                    $loai_quy = LoaiQuy::find($loai_quy_id);
                    if ($loai_quy) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $so_tien;
                        $loai_quy->save();
                    }
                }
            } else {
                $loai_quy = LoaiQuy::find($loai_quy_id);
                if ($loai_quy) {
                    if ($loai == 1) {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy + $so_tien - $old_tien;
                        $loai_quy->save();
                    } else {
                        $loai_quy->tong_tien_quy = $loai_quy->tong_tien_quy - $so_tien + $old_tien;
                        $loai_quy->save();
                    }
                }
            }

            return redirect()->route('admin.so.quy.index')->with('success', 'Chỉnh sửa sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $soquy = SoQuy::find($id);
            if (!$soquy || $soquy->deleted_at != null) {
                return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
            }

            $soquy->delete();

            return redirect()->back()->with('success', 'Đã xoá sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
