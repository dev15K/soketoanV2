<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuSanXuatChiTiet;
use App\Models\NguyenLieuTho;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminNguyenLieuSanXuatController extends Controller
{
    public function index()
    {
        $datas = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->paginate(20);

        $nltinhs = nguyenLieuSanXuat::where('trang_thai', '!=', TrangThainguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();

        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_san_xuat.index', compact('datas', 'nlphanloais', 'nltinhs', 'nlthos'));
    }

    public function detail($id)
    {
        $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
        if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
        }

        $nltinhs = nguyenLieuSanXuat::where('trang_thai', '!=', TrangThainguyenLieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();

        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        $dsNLSXChiTiet = NguyenLieuSanXuatChiTiet::where('nguyen_lieu_san_xuat_id', $id)
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.nguyen_lieu_san_xuat.detail', compact('nguyen_lieu_san_xuat', 'nlphanloais', 'dsNLSXChiTiet', 'nltinhs', 'nlthos'));
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

    private function saveData(NguyenLieuSanXuat $nguyenLieuSanXuat, Request $request)
    {
        $ngay = $request->input('ngay');
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');

        $tong_khoi_luong = 0;
        $gia_tien = 0;

        if (!$nguyenLieuSanXuat->code) {
            do {
                $code = $this->generateRandomString(8);
            } while (nguyenLieuSanXuat::where('code', $code)->where('id', '!=', $nguyenLieuSanXuat->id)->exists());

            $nguyenLieuSanXuat->code = $code;
        }

        $trang_thai = $request->input('trang_thai');

        $nguyenLieuSanXuat->ten_nguyen_lieu = $ten_nguyen_lieu;
        $nguyenLieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $nguyenLieuSanXuat->trang_thai = $trang_thai;

        $nguyenLieuSanXuat->tong_khoi_luong = $tong_khoi_luong;

        return $nguyenLieuSanXuat;
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

    private function saveDataChiTiet(NguyenLieuSanXuat $nguyenLieuSanXuat, Request $request)
    {
        $nguyen_lieu_phan_loai_ids = $request->input('nguyen_lieu_phan_loai_ids');
        $ten_nguyen_lieus = $request->input('ten_nguyen_lieus');
        $khoi_luongs = $request->input('khoi_luongs');

        $tong_khoi_luong = 0;
        $gia_tien = 0;

        nguyenLieuSanXuatChiTiet::where('nguyen_lieu_san_xuat_id', $nguyenLieuSanXuat->id)
            ->whereNotIn('nguyen_lieu_phan_loai_id', $nguyen_lieu_phan_loai_ids)
            ->delete();

        for ($i = 0; $i < count($nguyen_lieu_phan_loai_ids); $i++) {
            $nguyen_lieu_phan_loai_id = $nguyen_lieu_phan_loai_ids[$i];
            $ten_nguyen_lieu = $ten_nguyen_lieus[$i];
            $khoi_luong = $khoi_luongs[$i];

            $ngyen_lieu_phan_loai = NguyenLieuPhanLoai::find($nguyen_lieu_phan_loai_id);
            $so_tien = $khoi_luong * $ngyen_lieu_phan_loai->gia_sau_phan_loai;

            $oldData = nguyenLieuSanXuatChiTiet::where('nguyen_lieu_san_xuat_id', $nguyenLieuSanXuat->id)
                ->where('nguyen_lieu_phan_loai_id', $nguyen_lieu_phan_loai_id)
                ->first();


            if ($oldData) {
                $nguyenLieuSanXuatChiTiet = $oldData;
            } else {
                $nguyenLieuSanXuatChiTiet = new nguyenLieuSanXuatChiTiet();
            }

            $nguyenLieuSanXuatChiTiet->nguyen_lieu_san_xuat_id = $nguyenLieuSanXuat->id;
            $nguyenLieuSanXuatChiTiet->nguyen_lieu_phan_loai_id = $nguyen_lieu_phan_loai_id;
            $nguyenLieuSanXuatChiTiet->ten_nguyen_lieu = $ten_nguyen_lieu;
            $nguyenLieuSanXuatChiTiet->khoi_luong = $khoi_luong;
            $nguyenLieuSanXuatChiTiet->so_tien = $so_tien;

            $nguyenLieuSanXuatChiTiet->save();

            $tong_khoi_luong += $khoi_luong;
            $gia_tien += $so_tien;
        }

        $nguyenLieuSanXuat->tong_khoi_luong = $tong_khoi_luong;
        $nguyenLieuSanXuat->gia_tien = $gia_tien;

        $nguyenLieuSanXuat->save();
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_san_xuat = nguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThainguyenLieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
            }

            $nguyen_lieu_san_xuat->trang_thai = TrangThainguyenLieuSanXuat::DELETED();
            $nguyen_lieu_san_xuat->save();

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $nguyen_lieu_san_xuat = nguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThainguyenLieuSanXuat::DELETED()) {
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
