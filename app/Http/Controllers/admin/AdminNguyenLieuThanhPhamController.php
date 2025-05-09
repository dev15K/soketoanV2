<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminNguyenLieuThanhPhamController extends Controller
{
    public function index()
    {
        $datas = NguyenLieuThanhPham::where('trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED())
            ->orderByDesc('id')
            ->paginate(20);

        $nlsanxuats = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_thanh_pham.index', compact('datas', 'nlsanxuats'));
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

        return view('admin.pages.nguyen_lieu_thanh_pham.detail', compact('nguyenLieuThanhPham', 'nlsanxuats'));
    }

    public function store(Request $request)
    {
        try {
            $nguyenLieuThanhPham = new NguyenLieuThanhPham();

            $nguyenLieuThanhPham = $this->saveData($nguyenLieuThanhPham, $request);
            $nguyenLieuThanhPham->save();

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu thành phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(NguyenLieuThanhPham $phieuSanXuat, Request $request)
    {
        $ten_san_pham = $request->input('ten_san_pham');
        $ngay = $request->input('ngay');
        $type = $request->input('type');
        $nguyen_lieu_id = $request->input('nguyen_lieu_id');
        $khoi_luong = $request->input('khoi_luong');
        $don_vi_tinh = $request->input('don_vi_tinh');
        $so_luong = $request->input('so_luong');
        $price = $request->input('price');
        $total_price = $request->input('total_price');
        $ngay_san_xuat = $request->input('ngay_san_xuat');
        $han_su_dung = $request->input('han_su_dung');
        $trang_thai = $request->input('trang_thai');

        if (!$phieuSanXuat->product_code) {
            do {
                $code = $this->generateRandomString(8);
            } while (NguyenLieuThanhPham::where('product_code', $code)->where('id', '!=', $phieuSanXuat->id)->exists());

            $phieuSanXuat->product_code = $code;
        }

        $phieuSanXuat->ten_san_pham = $ten_san_pham;
        $phieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $phieuSanXuat->type = $type;
        $phieuSanXuat->nguyen_lieu_id = $nguyen_lieu_id;
        $phieuSanXuat->khoi_luong = $khoi_luong;
        $phieuSanXuat->don_vi_tinh = $don_vi_tinh;
        $phieuSanXuat->so_luong = $so_luong;
        $phieuSanXuat->price = $price;
        $phieuSanXuat->total_price = $total_price;
        $phieuSanXuat->ngay_san_xuat = Carbon::parse($ngay_san_xuat)->format('Y-m-d');
        $phieuSanXuat->han_su_dung = Carbon::parse($han_su_dung)->format('Y-m-d');
        $phieuSanXuat->trang_thai = $trang_thai;

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
            $nguyenLieuThanhPham->save();

            return redirect()->route('admin.nguyen.lieu.thanh.pham.index')->with('success', 'Chỉnh sửa nguyên liệu thành phẩm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
