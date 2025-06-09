<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiKhachHang;
use App\Http\Controllers\Controller;
use App\Models\KhachHang;
use Illuminate\Http\Request;

class AdminKhachHangController extends Controller
{
    public function index()
    {
        $datas = KhachHang::where('trang_thai', '!=', TrangThaiKhachHang::DELETED())
            ->orderByDesc('id')
            ->paginate(10);
        return view('admin.pages.khach_hang.index', compact('datas'));
    }

    public function detail($id)
    {
        $khachhang = KhachHang::find($id);
        if (!$khachhang || $khachhang->trang_thai == TrangThaiKhachHang::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
        }
        return view('admin.pages.khach_hang.detail', compact('khachhang'));
    }

    public function store(Request $request)
    {
        try {
            $ten = $request->input('ten');
            $dia_chi = $request->input('dia_chi');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $tinh_thanh = $request->input('tinh_thanh');
            $trang_thai = $request->input('trang_thai');

            $khachhang = new KhachHang();

            $khachhang->ten = $ten;
            $khachhang->dia_chi = $dia_chi ?? '';
            $khachhang->so_dien_thoai = $so_dien_thoai ?? '';
            $khachhang->tinh_thanh = $tinh_thanh ?? '';
            $khachhang->trang_thai = $trang_thai;
            $khachhang->save();

            return redirect()->back()->with('success', 'Thêm mới nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $ten = $request->input('ten');
            $dia_chi = $request->input('dia_chi');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $tinh_thanh = $request->input('tinh_thanh');
            $trang_thai = $request->input('trang_thai');

            $khachhang = KhachHang::find($id);
            if (!$khachhang || $khachhang->trang_thai == TrangThaiKhachHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
            }

            $khachhang->ten = $ten;
            $khachhang->dia_chi = $dia_chi ?? '';
            $khachhang->so_dien_thoai = $so_dien_thoai ?? '';
            $khachhang->tinh_thanh = $tinh_thanh ?? '';
            $khachhang->trang_thai = $trang_thai;
            $khachhang->save();

            return redirect()->route('admin.khach.hang.index')->with('success', 'Chỉnh sửa nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $khachhang = KhachHang::find($id);
            if (!$khachhang || $khachhang->trang_thai == TrangThaiKhachHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
            }

            $khachhang->trang_thai = TrangThaiKhachHang::DELETED();
            $khachhang->save();

            return redirect()->back()->with('success', 'Đã xoá nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
