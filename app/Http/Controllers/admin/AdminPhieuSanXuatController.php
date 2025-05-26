<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Enums\TrangThaiPhieuSanXuat;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuTho;
use App\Models\NguyenLieuTinh;
use App\Models\PhieuSanXuat;
use App\Models\PhieuSanXuatChiTiet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminPhieuSanXuatController extends Controller
{
    public function index(Request $request)
    {
        $ngay = $request->input('ngay');
        $keyword = $request->input('keyword');
        $nguyen_lieu_id = $request->input('nguyen_lieu_id');

        $queries = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED());

        if ($ngay) {
            $queries->whereDate('ngay', \Carbon\Carbon::parse($ngay)->format('Y-m-d'));
        }

        if ($keyword) {
            $queries->where(function ($q) use ($keyword) {
                $q->where('so_lo_san_xuat', 'like', '%' . $keyword . '%')
                    ->orWhere('code', 'like', '%' . $keyword . '%');
            });
        }

        if ($nguyen_lieu_id) {
            $queries->where('nguyen_lieu_id', $nguyen_lieu_id);
        }

        $datas = $queries->orderByDesc('id')->paginate(20);

        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
            ->orderByDesc('id')
            ->get();

        $code = $this->generateCode();
        $so_lo_san_xuat = $this->generateLoSanXuat();

        return view('admin.pages.phieu_san_xuat.index', compact('datas', 'code', 'so_lo_san_xuat', 'nltinhs', 'ngay', 'keyword', 'nguyen_lieu_id'));
    }

    public function detail($id)
    {
        $phieu_san_xuat = PhieuSanXuat::find($id);
        if (!$phieu_san_xuat || $phieu_san_xuat->trang_thai == TrangThaiPhieuSanXuat::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy phiếu sản xuất');
        }

        $nltinhs = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
            ->orderByDesc('id')
            ->get();
        $code = $phieu_san_xuat->code;
        if (empty($code)) {
            $code = $this->generateCode();
        }

        $so_lo_san_xuat = $phieu_san_xuat->so_lo_san_xuat;
        if (empty($so_lo_san_xuat)) {
            $so_lo_san_xuat = $this->generateLoSanXuat();
        }

        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        $dsNLSXChiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $id)
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.phieu_san_xuat.detail', compact('phieu_san_xuat', 'nlphanloais', 'dsNLSXChiTiets', 'nltinhs', 'code', 'so_lo_san_xuat'));
    }

    public function store(Request $request)
    {
        try {
            $phieu_san_xuat = new PhieuSanXuat();

            $phieu_san_xuat = $this->saveData($phieu_san_xuat, $request);
            $phieu_san_xuat->save();

            return redirect()->back()->with('success', 'Thêm mới phiếu sản xuất thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(PhieuSanXuat $phieuSanXuat, Request $request)
    {
        $ngay = $request->input('ngay');
        $code = $request->input('code');
        $tong_khoi_luong = $request->input('tong_khoi_luong');
        $so_lo_san_xuat = $request->input('so_lo_san_xuat');
        $nguyen_lieu_id = $request->input('nguyen_lieu_id');

        if (!$phieuSanXuat->code) {
            $phieuSanXuat->code = $code;
        }

        $trang_thai = $request->input('trang_thai');

        $phieuSanXuat->so_lo_san_xuat = $so_lo_san_xuat;
        $phieuSanXuat->nguyen_lieu_id = $nguyen_lieu_id;
        $phieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $phieuSanXuat->trang_thai = $trang_thai;
        $phieuSanXuat->tong_khoi_luong = $tong_khoi_luong;

        return $phieuSanXuat;
    }

    private function saveDataChiTiet(PhieuSanXuat $phieuSanXuat, Request $request)
    {
        $loai_nguyen_lieu_ids = $request->input('loai_nguyen_lieu_ids');
        $nguyen_lieu_ids = $request->input('nguyen_lieu_ids');
        $ten_nguyen_lieus = $request->input('ten_nguyen_lieus');
        $khoi_luongs = $request->input('khoi_luongs');

        $tong_khoi_luong = 0;

        PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieuSanXuat->id)
            ->whereNotIn('nguyen_lieu_id', $nguyen_lieu_ids)
            ->delete();

        for ($i = 0; $i < count($loai_nguyen_lieu_ids); $i++) {
            $loai_nguyen_lieu_id = $loai_nguyen_lieu_ids[$i];
            $nguyen_lieu_phan_loai_id = $nguyen_lieu_ids[$i];
            $ten_nguyen_lieu = $ten_nguyen_lieus[$i];
            $khoi_luong = $khoi_luongs[$i];

            $oldData = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieuSanXuat->id)
                ->where('nguyen_lieu_id', $nguyen_lieu_phan_loai_id)
                ->first();

            if ($oldData) {
                $phieuSanXuatChiTiet = $oldData;
            } else {
                $phieuSanXuatChiTiet = new PhieuSanXuatChiTiet();
            }

            $phieuSanXuatChiTiet->type = $loai_nguyen_lieu_id;
            $phieuSanXuatChiTiet->phieu_san_xuat_id = $phieuSanXuat->id;
            $phieuSanXuatChiTiet->nguyen_lieu_id = $nguyen_lieu_phan_loai_id;
            $phieuSanXuatChiTiet->ten_nguyen_lieu = $ten_nguyen_lieu;
            $phieuSanXuatChiTiet->khoi_luong = $khoi_luong;
            $phieuSanXuatChiTiet->so_tien = 0;

            $phieuSanXuatChiTiet->save();

            $tong_khoi_luong += $khoi_luong;
        }

        $phieuSanXuat->tong_khoi_luong = $tong_khoi_luong;

        $phieuSanXuat->save();
    }

    public function delete($id)
    {
        try {
            $phieu_san_xuat = PhieuSanXuat::find($id);
            if (!$phieu_san_xuat || $phieu_san_xuat->trang_thai == TrangThaiphieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy phiếu sản xuất');
            }

            $phieu_san_xuat->trang_thai = TrangThaiphieuSanXuat::DELETED();
            $phieu_san_xuat->save();

            return redirect()->back()->with('success', 'Đã xoá phiếu sản xuất thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $phieu_san_xuat = PhieuSanXuat::find($id);
            if (!$phieu_san_xuat || $phieu_san_xuat->trang_thai == TrangThaiphieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy phiếu sản xuất');
            }

            $phieu_san_xuat = $this->saveData($phieu_san_xuat, $request);
            $phieu_san_xuat->save();

            return redirect()->route('admin.phieu.san.xuat.index')->with('success', 'Chỉnh sửa phiếu sản xuất thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function generateCode()
    {
        $lastItem = PhieuSanXuat::orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    private function generateLoSanXuat()
    {
        $lastItem = PhieuSanXuat::orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return generateLSXCode($lastId + 1);
    }
}
