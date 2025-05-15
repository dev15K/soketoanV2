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
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu sản xuất');
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

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu sản xuất thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(NguyenLieuSanXuat $nguyenLieuSanXuat, Request $request)
    {
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');
        $ngay = $request->input('ngay');
        $phieu_san_xuat_id = $request->input('phieu_san_xuat_id');
        $khoi_luong = $request->input('khoi_luong');
        $don_vi_tinh = $request->input('don_vi_tinh');
        $mau_sac = $request->input('mau_sac');
        $mui_thom = $request->input('mui_thom');
        $chi_tiet_khac = $request->input('chi_tiet_khac');
        $bao_quan = $request->input('bao_quan');
        $trang_thai = $request->input('trang_thai');

        if (!$nguyenLieuSanXuat->code) {
            do {
                $code = generateRandomString(8);
            } while (NguyenLieuSanXuat::where('code', $code)->where('id', '!=', $nguyenLieuSanXuat->id)->exists());

            $nguyenLieuSanXuat->code = $code;
        }

        $nguyenLieuSanXuat->ten_nguyen_lieu = $ten_nguyen_lieu;
        $nguyenLieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $nguyenLieuSanXuat->phieu_san_xuat_id = $phieu_san_xuat_id;
        $nguyenLieuSanXuat->khoi_luong = $khoi_luong;
        $nguyenLieuSanXuat->don_vi_tinh = $don_vi_tinh;
        $nguyenLieuSanXuat->mau_sac = $mau_sac;
        $nguyenLieuSanXuat->mui_thom = $mui_thom;
        $nguyenLieuSanXuat->chi_tiet_khac = $chi_tiet_khac;
        $nguyenLieuSanXuat->bao_quan = $bao_quan;
        $nguyenLieuSanXuat->trang_thai = $trang_thai;

        return $nguyenLieuSanXuat;
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu sản xuất');
            }

            $nguyen_lieu_san_xuat->trang_thai = TrangThaiNguyenLieuSanXuat::DELETED();
            $nguyen_lieu_san_xuat->save();

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu sản xuất thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu sản xuất');
            }

            $nguyen_lieu_san_xuat = $this->saveData($nguyen_lieu_san_xuat, $request);
            $nguyen_lieu_san_xuat->save();

            return redirect()->route('admin.nguyen.lieu.san.xuat.index')->with('success', 'Chỉnh sửa nguyên liệu sản xuất thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
