<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Enums\TrangThaiSanPham;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminNguyenLieuThanhPhamController extends Controller
{
    public function index(Request $request)
    {
        $ngay_search = $request->input('ngay');
        $nguyen_lieu_san_xuat_id_search = $request->input('nguyen_lieu_san_xuat_id');
        $san_pham_id_search = $request->input('san_pham_id');

        $queries = NguyenLieuThanhPham::where('trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED());

        if ($ngay_search) {
            $queries->whereDate('ngay', Carbon::parse($ngay_search)->format('Y-m-d'));
        }

        if ($nguyen_lieu_san_xuat_id_search) {
            $queries->where('nguyen_lieu_san_xuat_id', $nguyen_lieu_san_xuat_id_search);
        }

        if ($san_pham_id_search) {
            $queries->where('san_pham_id', $san_pham_id_search);
        }

        $datas = $queries->orderByDesc('id')->paginate(20);

        $nlsanxuats = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $products = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_thanh_pham.index', compact('datas', 'nlsanxuats', 'products', 'ngay_search', 'nguyen_lieu_san_xuat_id_search', 'san_pham_id_search'));
    }

    public function detail($id)
    {
        $nguyenLieuThanhPham = NguyenLieuThanhPham::find($id);
        if (!$nguyenLieuThanhPham || $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thành phẩm');
        }

        $nlsanxuats = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $products = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_thanh_pham.detail', compact('nguyenLieuThanhPham', 'nlsanxuats', 'products'));
    }

    public function store(Request $request)
    {
        try {
            $nguyenLieuThanhPham = new NguyenLieuThanhPham();

            $nguyenLieuThanhPham = $this->saveData($nguyenLieuThanhPham, $request);
            if (!$nguyenLieuThanhPham) {
                return redirect()->back()->with('error', 'Số lượng không đủ!');
            }
            $nguyenLieuThanhPham->save();

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu thành phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(NguyenLieuThanhPham $nguyenLieuThanhPham, Request $request)
    {
        $ngay = $request->input('ngay');
        $nguyen_lieu_san_xuat_id = $request->input('nguyen_lieu_san_xuat_id');
        $san_pham_id = $request->input('san_pham_id');
        $ten_san_pham = $request->input('ten_san_pham');
        $so_luong = $request->input('so_luong');
        $price = $request->input('price');
        $total_price = $request->input('total_price');
        $ngay_san_xuat = $request->input('ngay_san_xuat');
        $trang_thai = $request->input('trang_thai');
        $khoi_luong_da_dung = $request->input('khoi_luong_da_dung');

        $oldNguyenLieuSanXuatId = $nguyenLieuThanhPham->nguyen_lieu_san_xuat_id;
        $oldKhoiLuongDaDung = $nguyenLieuThanhPham->khoi_luong_da_dung;

        $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyen_lieu_san_xuat_id);
        if ($nguyenLieuSanXuat) {
            $khoi_luong = $nguyenLieuSanXuat->khoi_luong - $nguyenLieuSanXuat->khoi_luong_da_dung;
            if ($khoi_luong < $khoi_luong_da_dung) {
                return false;
            }
        }

        $nguyenLieuThanhPham->ten_san_pham = $ten_san_pham;
        $nguyenLieuThanhPham->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $nguyenLieuThanhPham->so_luong = $so_luong;
        $nguyenLieuThanhPham->price = $price;
        $nguyenLieuThanhPham->total_price = $total_price;
        $nguyenLieuThanhPham->nguyen_lieu_san_xuat_id = $nguyen_lieu_san_xuat_id;
        $nguyenLieuThanhPham->san_pham_id = $san_pham_id;
        $nguyenLieuThanhPham->ngay_san_xuat = Carbon::parse($ngay_san_xuat)->format('Y-m-d');
        $nguyenLieuThanhPham->trang_thai = $trang_thai;
        $nguyenLieuThanhPham->khoi_luong_da_dung = $khoi_luong_da_dung;

        if ($oldNguyenLieuSanXuatId != $nguyen_lieu_san_xuat_id) {
            $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyen_lieu_san_xuat_id);
            if ($nguyenLieuSanXuat) {
                $nguyenLieuSanXuat->khoi_luong_da_dung += $khoi_luong_da_dung;
                $nguyenLieuSanXuat->save();
            }

            $oldNguyenLieuSanXuat = NguyenLieuSanXuat::find($oldNguyenLieuSanXuatId);
            if ($oldNguyenLieuSanXuat) {
                $oldNguyenLieuSanXuat->khoi_luong_da_dung -= $oldKhoiLuongDaDung;
                $oldNguyenLieuSanXuat->save();
            }
        } else {
            $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyen_lieu_san_xuat_id);
            if ($nguyenLieuSanXuat) {
                $nguyenLieuSanXuat->khoi_luong_da_dung += $khoi_luong_da_dung - $oldKhoiLuongDaDung;
                $nguyenLieuSanXuat->save();
            }
        }

        return $nguyenLieuThanhPham;
    }

    public function delete($id)
    {
        try {
            $nguyenLieuThanhPham = NguyenLieuThanhPham::find($id);
            if (!$nguyenLieuThanhPham || $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thành phẩm');
            }

            $nguyenLieuThanhPham->trang_thai = TrangThaiNguyenLieuThanhPham::DELETED();
            $nguyenLieuThanhPham->save();

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu thành phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $nguyenLieuThanhPham = NguyenLieuThanhPham::find($id);
            if (!$nguyenLieuThanhPham || $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thành phẩm');
            }

            $nguyenLieuThanhPham = $this->saveData($nguyenLieuThanhPham, $request);
            if (!$nguyenLieuThanhPham) {
                return redirect()->back()->with('error', 'Số lượng không đủ!');
            }
            $nguyenLieuThanhPham->save();

            return redirect()->route('admin.nguyen.lieu.thanh.pham.index')->with('success', 'Chỉnh sửa nguyên liệu thành phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
