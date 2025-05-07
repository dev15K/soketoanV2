<?php

namespace App\Http\Controllers;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
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
        $nltinhs = nguyenLieuSanXuat::where('trang_thai', '!=', TrangThainguyenLieuSanXuat::DELETED())
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
}
