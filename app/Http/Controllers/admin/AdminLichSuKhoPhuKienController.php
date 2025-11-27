<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KhoPhuKien;
use App\Models\LichSuKhoPhuKien;
use Illuminate\Http\Request;

class AdminLichSuKhoPhuKienController extends Controller
{
    public function list(Request $request)
    {
        $kho_phu_kien_id = $request->input('kho_phu_kien_id');

        $datas = LichSuKhoPhuKien::where('kho_phu_kien_id', $kho_phu_kien_id)
            ->orderByDesc('id')
            ->get();

        $kho = KhoPhuKien::find($kho_phu_kien_id);
        if (!$kho) {
            return redirect(route('admin.kho.phu.kien.index'))->with('error', 'Không tìm thấy kho phụ kiện');
        }
        return view('admin.pages.lich_su_kho_phu_kien.index', compact('datas', 'kho'));
    }

    public function store(Request $request)
    {
        try {
            $ls = new LichSuKhoPhuKien();

            $kho_phu_kien_id = $request->input('kho_phu_kien_id');
            $so_luong = $request->input('so_luong');

            $kho = KhoPhuKien::find($kho_phu_kien_id);

            if (!$kho) {
                return redirect()->back()->with('error', 'Không tìm thấy kho phụ kiện');
            }

            $ls->kho_phu_kien_id = $kho_phu_kien_id;
            $ls->so_luong = $so_luong;
            $ls->loai_phu_kien_id = $kho->loai_phu_kien_id;
            $ls->save();

            $kho = KhoPhuKien::find($ls->kho_phu_kien_id);
            if ($kho) {
                $kho->so_luong = $kho->so_luong + $ls->so_luong;
                $kho->save();
            }

            return redirect()->back()->with('success', 'Tạo mới lịch sử kho phụ kiện thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $ls = LichSuKhoPhuKien::find($id);
            if (!$ls) {
                return redirect()->back()->with('error', 'Không tìm thấy lịch sử kho phụ kiện');
            }

            $kho = KhoPhuKien::find($ls->kho_phu_kien_id);
            if ($kho) {
                $kho->so_luong = $kho->so_luong - $ls->so_luong;
                $kho->save();
            }
            $ls->delete();
            return redirect()->back()->with('success', 'Đã xoá lịch sử kho phụ kiện thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
