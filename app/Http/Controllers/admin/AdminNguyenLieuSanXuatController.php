<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiPhieuSanXuat;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuSanXuat;
use App\Models\PhieuSanXuat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminNguyenLieuSanXuatController extends Controller
{
    public function index()
    {
        $datas = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->paginate(20);

        $phieu_san_xuats = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_san_xuat.index', compact('datas', 'phieu_san_xuats'));
    }

    public function detail($id)
    {
        $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
        if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
        }

        $phieu_san_xuats = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_san_xuat.detail', compact('nguyen_lieu_san_xuat', 'phieu_san_xuats'));
    }

    public function store(Request $request)
    {
        try {
            $nguyen_lieu_san_xuat = new NguyenLieuSanXuat();

            $nguyen_lieu_san_xuat = $this->saveData($nguyen_lieu_san_xuat, $request);
            $nguyen_lieu_san_xuat->save();

            $this->saveDataChiTiet($nguyen_lieu_san_xuat, $request);

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(NguyenLieuSanXuat $phieuSanXuat, Request $request)
    {
        $ngay = $request->input('ngay');
        $ten_phieu = $request->input('ten_phieu');

        $tong_khoi_luong = 0;
        $gia_tien = 0;

        if (!$phieuSanXuat->code) {
            do {
                $code = $this->generateRandomString(8);
            } while (NguyenLieuSanXuat::where('code', $code)->where('id', '!=', $phieuSanXuat->id)->exists());

            $phieuSanXuat->code = $code;
        }

        $trang_thai = $request->input('trang_thai');

        $phieuSanXuat->ten_phieu = $ten_phieu;
        $phieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $phieuSanXuat->trang_thai = $trang_thai;

        $phieuSanXuat->tong_khoi_luong = $tong_khoi_luong;

        return $phieuSanXuat;
    }

    private function generateRandomString($length)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijkmnopqrstuyvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
            }

            $nguyen_lieu_san_xuat->trang_thai = TrangThaiNguyenLieuSanXuat::DELETED();
            $nguyen_lieu_san_xuat->save();

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
            }

            $nguyen_lieu_san_xuat = $this->saveData($nguyen_lieu_san_xuat, $request);
            $nguyen_lieu_san_xuat->save();

            $this->saveDataChiTiet($nguyen_lieu_san_xuat, $request);

            return redirect()->route('admin.nguyen.lieu.tinh.index')->with('success', 'Chỉnh sửa nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
