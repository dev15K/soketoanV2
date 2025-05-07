<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

class AdminNguyenLieuThanhPhamController extends Controller
{
    public function index()
    {
        $datas = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->paginate(20);

        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiphieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();

        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.phieu_san_xuat.index', compact('datas', 'nlphanloais', 'nltinhs', 'nlthos'));
    }

    public function detail($id)
    {
        $phieu_san_xuat = PhieuSanXuat::find($id);
        if (!$phieu_san_xuat || $phieu_san_xuat->trang_thai == TrangThaiPhieuSanXuat::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
        }

        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiphieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();

        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        $dsNLSXChiTiet = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $id)
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.phieu_san_xuat.detail', compact('phieu_san_xuat', 'nlphanloais', 'dsNLSXChiTiet', 'nltinhs', 'nlthos'));
    }

    public function store(Request $request)
    {
        try {
            $phieu_san_xuat = new PhieuSanXuat();

            $phieu_san_xuat = $this->saveData($phieu_san_xuat, $request);
            $phieu_san_xuat->save();

            $this->saveDataChiTiet($phieu_san_xuat, $request);

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(PhieuSanXuat $phieuSanXuat, Request $request)
    {
        $ngay = $request->input('ngay');
        $ten_phieu = $request->input('ten_phieu');

        $tong_khoi_luong = 0;
        $gia_tien = 0;

        if (!$phieuSanXuat->code) {
            do {
                $code = $this->generateRandomString(8);
            } while (NguyenLieuTinh::where('code', $code)->where('id', '!=', $phieuSanXuat->id)->exists());

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

    private function saveDataChiTiet(PhieuSanXuat $phieuSanXuat, Request $request)
    {
        $nguyen_lieu_phan_loai_ids = $request->input('nguyen_lieu_phan_loai_ids');
        $ten_phieus = $request->input('ten_phieus');
        $khoi_luongs = $request->input('khoi_luongs');

        $tong_khoi_luong = 0;
        $gia_tien = 0;

        PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieuSanXuat->id)
            ->whereNotIn('nguyen_lieu_phan_loai_id', $nguyen_lieu_phan_loai_ids)
            ->delete();

        for ($i = 0; $i < count($nguyen_lieu_phan_loai_ids); $i++) {
            $nguyen_lieu_phan_loai_id = $nguyen_lieu_phan_loai_ids[$i];
            $ten_phieu = $ten_phieus[$i];
            $khoi_luong = $khoi_luongs[$i];

            $ngyen_lieu_phan_loai = NguyenLieuPhanLoai::find($nguyen_lieu_phan_loai_id);
            $so_tien = $khoi_luong * $ngyen_lieu_phan_loai->gia_sau_phan_loai;

            $oldData = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieuSanXuat->id)
                ->where('nguyen_lieu_phan_loai_id', $nguyen_lieu_phan_loai_id)
                ->first();


            if ($oldData) {
                $phieuSanXuatChiTiet = $oldData;
            } else {
                $phieuSanXuatChiTiet = new PhieuSanXuatChiTiet();
            }

            $phieuSanXuatChiTiet->phieu_san_xuat_id = $phieuSanXuat->id;
            $phieuSanXuatChiTiet->nguyen_lieu_phan_loai_id = $nguyen_lieu_phan_loai_id;
            $phieuSanXuatChiTiet->ten_phieu = $ten_phieu;
            $phieuSanXuatChiTiet->khoi_luong = $khoi_luong;
            $phieuSanXuatChiTiet->so_tien = $so_tien;

            $phieuSanXuatChiTiet->save();

            $tong_khoi_luong += $khoi_luong;
            $gia_tien += $so_tien;
        }

        $phieuSanXuat->tong_khoi_luong = $tong_khoi_luong;

        $phieuSanXuat->save();
    }

    public function delete($id)
    {
        try {
            $phieu_san_xuat = PhieuSanXuat::find($id);
            if (!$phieu_san_xuat || $phieu_san_xuat->trang_thai == TrangThaiphieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
            }

            $phieu_san_xuat->trang_thai = TrangThaiphieuSanXuat::DELETED();
            $phieu_san_xuat->save();

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $phieu_san_xuat = PhieuSanXuat::find($id);
            if (!$phieu_san_xuat || $phieu_san_xuat->trang_thai == TrangThaiphieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
            }

            $phieu_san_xuat = $this->saveData($phieu_san_xuat, $request);
            $phieu_san_xuat->save();

            $this->saveDataChiTiet($phieu_san_xuat, $request);

            return redirect()->route('admin.nguyen.lieu.tinh.index')->with('success', 'Chỉnh sửa nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
