<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiBanHang;
use App\Enums\TrangThaiKhachHang;
use App\Http\Controllers\Controller;
use App\Models\BanHang;
use App\Models\KhachHang;
use Illuminate\Http\Request;

class AdminBanHangController extends Controller
{
    public function index()
    {
        $datas = BanHang::where('trang_thai', '!=', TrangThaiBanHang::DELETED())
            ->orderByDesc('id')
            ->paginate(20);

        $khachhangs = KhachHang::where('trang_thai', '!=', TrangThaiKhachHang::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.ban_hang.index', compact('datas', 'khachhangs'));
    }

    public function detail($id)
    {
        $banhang = BanHang::find($id);
        if (!$banhang || $banhang->trang_thai == TrangThaiKhachHang::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
        }
        return view('admin.pages.ban_hang.detail', compact('banhang'));
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
            $khachhang->dia_chi = $dia_chi;
            $khachhang->so_dien_thoai = $so_dien_thoai;
            $khachhang->tinh_thanh = $tinh_thanh;
            $khachhang->trang_thai = $trang_thai;
            $khachhang->save();

            return redirect()->back()->with('success', 'Thêm mới hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
                return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
            }

            $khachhang->ten = $ten;
            $khachhang->dia_chi = $dia_chi;
            $khachhang->so_dien_thoai = $so_dien_thoai;
            $khachhang->tinh_thanh = $tinh_thanh;
            $khachhang->trang_thai = $trang_thai;
            $khachhang->save();

            return redirect()->route('admin.khach.hang.index')->with('success', 'Chỉnh sửa hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $khachhang = KhachHang::find($id);
            if (!$khachhang || $khachhang->trang_thai == TrangThaiKhachHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
            }

            $khachhang->trang_thai = TrangThaiKhachHang::DELETED();
            $khachhang->save();

            return redirect()->back()->with('success', 'Đã xoá hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
