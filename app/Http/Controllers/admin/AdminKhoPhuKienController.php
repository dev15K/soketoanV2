<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiSanPham;
use App\Http\Controllers\Controller;
use App\Models\KhoPhuKien;
use App\Models\LoaiPhuKien;
use App\Models\SanPham;

class AdminKhoPhuKienController extends Controller
{
    public function index()
    {
        $datas = KhoPhuKien::where('deleted_at', null)->orderByDesc('id')->get();

        $products = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->orderByDesc('id')
            ->get();

        $loaiPhuKiens = LoaiPhuKien::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.phu_kien_san_pham.index', compact('datas', 'products', 'loaiPhuKiens'));
    }
}
