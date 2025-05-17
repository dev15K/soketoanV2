<?php

namespace App\Http\Controllers;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Enums\TrangThaiSanPham;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuTinh;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect(route('admin.home'));
        }
        return redirect(route('auth.processLogin'));
    }

    public function listNguyenLieuTinh()
    {
        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
            ->orderByDesc('id')
            ->get();

        $data = returnMessage(1, $nltinhs, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuPhanLoai()
    {
        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        $data = returnMessage(1, $nlphanloais, 'Success!');
        return response()->json($data);
    }

    public function thongTinSanPham(Request $request)
    {
        $id = $request->get('id');
        $sanpham = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->where('id', $id)
            ->first();

        $data = returnMessage(1, $sanpham, 'Success!');
        return response()->json($data);
    }
}
