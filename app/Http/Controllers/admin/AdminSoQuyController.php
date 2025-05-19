<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiKhachHang;
use App\Http\Controllers\Controller;
use App\Models\SoQuy;
use Illuminate\Http\Request;

class AdminSoQuyController extends Controller
{
    public function index()
    {
        $datas = SoQuy::where('deleted_at', null)
            ->orderByDesc('id')
            ->paginate(20);

        $ma_phieu = $this->generateCode();
        return view('admin.pages.so_quy.index', compact('datas', 'ma_phieu'));
    }

    private function generateCode()
    {
        $lastItem = SoQuy::where('deleted_at', null)
            ->orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    public function detail($id)
    {
        $soquy = SoQuy::find($id);
        if (!$soquy || $soquy->trang_thai == TrangThaiKhachHang::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
        }
        return view('admin.pages.so_quy.detail', compact('soquy'));
    }

    public function store(Request $request)
    {
        try {
            $loai = $request->input('loai');
            $so_tien = $request->input('so_tien');
            $noi_dung = $request->input('noi_dung');
            $ngay = $request->input('ngay');

            $soquy = new SoQuy();

            $ma_phieu = $request->input('ma_phieu');

            $soquy->ma_phieu = $ma_phieu;
            $soquy->loai = $loai;
            $soquy->so_tien = $so_tien;
            $soquy->noi_dung = $noi_dung;
            $soquy->ngay = $ngay;

            $soquy->save();

            return redirect()->back()->with('success', 'Thêm mới sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $loai = $request->input('loai');
            $so_tien = $request->input('so_tien');
            $noi_dung = $request->input('noi_dung');
            $ngay = $request->input('ngay');

            $soquy = SoQuy::find($id);
            if (!$soquy || $soquy->trang_thai == TrangThaiKhachHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
            }

            $soquy->loai = $loai;
            $soquy->so_tien = $so_tien;
            $soquy->noi_dung = $noi_dung;
            $soquy->ngay = $ngay;
            $soquy->save();

            return redirect()->route('admin.so.quy.index')->with('success', 'Chỉnh sửa sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $soquy = SoQuy::find($id);
            if (!$soquy || $soquy->trang_thai == TrangThaiKhachHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy sổ quỹ');
            }

            $soquy->delete();

            return redirect()->back()->with('success', 'Đã xoá sổ quỹ thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
