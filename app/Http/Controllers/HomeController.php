<?php

namespace App\Http\Controllers;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Enums\TrangThaiSanPham;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use App\Models\NguyenLieuTho;
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

    public function listNguyenLieuTho()
    {
        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();

        $data = returnMessage(1, $nlthos, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuPhanLoai()
    {
        $nlphanloais = NguyenLieuPhanLoai::where('nguyen_lieu_phan_loais.trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->join('nguyen_lieu_thos', 'nguyen_lieu_thos.id', '=', 'nguyen_lieu_phan_loais.nguyen_lieu_tho_id')
            ->orderByDesc('nguyen_lieu_phan_loais.id')
            ->select('nguyen_lieu_phan_loais.*', 'nguyen_lieu_thos.ten_nguyen_lieu as ten_nguyen_lieu_tho', 'nguyen_lieu_thos.code as ma_don_hang')
            ->get();

        $data = returnMessage(1, $nlphanloais, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuTinh()
    {
        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
            ->orderByDesc('id')
            ->get();

        $data = returnMessage(1, $nltinhs, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuSanXuat()
    {
        $nlsanxuats = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $data = returnMessage(1, $nlsanxuats, 'Success!');
        return response()->json($data);
    }

    public function listNguyenLieuThanhPham()
    {
        $nhthanhphams = NguyenLieuThanhPham::where('nguyen_lieu_thanh_phams.trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED())
            ->join('san_phams', 'san_phams.id', '=', 'nguyen_lieu_thanh_phams.san_pham_id')
            ->join('nguyen_lieu_san_xuats', 'nguyen_lieu_san_xuats.id', '=', 'nguyen_lieu_thanh_phams.nguyen_lieu_san_xuat_id')
            ->join('phieu_san_xuats', 'phieu_san_xuats.id', '=', 'nguyen_lieu_san_xuats.phieu_san_xuat_id')
            ->orderByDesc('nguyen_lieu_thanh_phams.id')
            ->select('nguyen_lieu_thanh_phams.*', 'san_phams.ten_san_pham as ten_san_pham', 'phieu_san_xuats.so_lo_san_xuat as so_lo_san_xuat')
            ->get();

        $data = returnMessage(1, $nhthanhphams, 'Success!');
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
