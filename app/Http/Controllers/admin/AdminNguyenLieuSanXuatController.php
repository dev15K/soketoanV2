<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiPhieuSanXuat;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuSanXuat;
use App\Models\PhieuSanXuat;
use App\Models\PhieuSanXuatChiTiet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminNguyenLieuSanXuatController extends Controller
{
    public function index(Request $request)
    {
        $ngay_search = $request->input('ngay');
        $keyword = $request->input('keyword');
        $phieu_san_xuat_id = $request->input('phieu_san_xuat_id');

        $queries = NguyenLieuSanXuat::where('trang_thai', '!=', TrangThaiNguyenLieuSanXuat::DELETED());

        if ($ngay_search) {
            $queries->whereDate('ngay', Carbon::parse($ngay_search)->format('Y-m-d'));
        }

        if ($keyword) {
            $queries->where('ten_nguyen_lieu', 'like', '%' . $keyword . '%');
        }

        if ($phieu_san_xuat_id) {
            $queries->where('phieu_san_xuat_id', $phieu_san_xuat_id);
        }

        $datas = $queries->orderByDesc('id')->paginate(10);
        $phieu_san_xuats = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $nsus = User::where('status', '!=', UserStatus::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_san_xuat.index', compact('datas', 'phieu_san_xuats',
            'ngay_search', 'keyword', 'phieu_san_xuat_id', 'nsus'));
    }

    public function detail($id)
    {
        $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
        if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy');
        }

        $phieu_san_xuats = PhieuSanXuat::where('trang_thai', '!=', TrangThaiPhieuSanXuat::DELETED())
            ->orderByDesc('id')
            ->get();

        $nsus = User::where('status', '!=', UserStatus::DELETED())
            ->orderByDesc('id')
            ->get();

        return view('admin.pages.nguyen_lieu_san_xuat.detail', compact('nguyen_lieu_san_xuat', 'phieu_san_xuats', 'nsus'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $nguyen_lieu_san_xuat = new NguyenLieuSanXuat();

            $nguyen_lieu_san_xuat = $this->saveData($nguyen_lieu_san_xuat, $request);
            if (!$nguyen_lieu_san_xuat) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Số lượng không đủ')->withInput();
            }
            $nguyen_lieu_san_xuat->save();

            DB::commit();
            return redirect()->back()->with('success', 'Thêm mới thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function saveData(NguyenLieuSanXuat $nguyenLieuSanXuat, Request $request)
    {
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');
        $ngay = $request->input('ngay');
        $phieu_san_xuat_id = $request->input('phieu_san_xuat_id');
        $khoi_luong = $request->input('khoi_luong');
        $don_vi_tinh = $request->input('don_vi_tinh') ?? '';
        $don_gia = $request->input('don_gia');
        $mau_sac = $request->input('mau_sac');
        $mui_thom = $request->input('mui_thom');
        $chi_tiet_khac = $request->input('chi_tiet_khac');
        $bao_quan = $request->input('bao_quan');
        $trang_thai = $request->input('trang_thai');
        $nhan_vien_san_xuat = $request->input('nhan_vien_san_xuat');

        $oldPhieuSanXuatId = $nguyenLieuSanXuat->phieu_san_xuat_id;
        $oldKhoiLuong = $nguyenLieuSanXuat->khoi_luong;

        $phieuSanXuat = PhieuSanXuat::find($phieu_san_xuat_id);
        if (!$phieuSanXuat || $phieuSanXuat->trang_thai == TrangThaiPhieuSanXuat::DELETED()) {
            return false;
        }

        if ($oldPhieuSanXuatId != $phieu_san_xuat_id) {
            $ton = $phieuSanXuat->tong_khoi_luong - $phieuSanXuat->khoi_luong_da_dung;
            if ($khoi_luong > $ton) {
                return false;
            }
        } else {
            $ton = $phieuSanXuat->tong_khoi_luong - $phieuSanXuat->khoi_luong_da_dung + $oldKhoiLuong;
            if ($khoi_luong > $ton) {
                return false;
            }
        }

        if (!$nguyenLieuSanXuat->code) {
            do {
                $code = generateRandomString(8);
            } while (NguyenLieuSanXuat::where('code', $code)->where('id', '!=', $nguyenLieuSanXuat->id)->exists());

            $nguyenLieuSanXuat->code = $code;
        }

        if (!$don_gia || $don_gia <= 0 || !is_numeric($don_gia) || $don_gia == '') {
            $chiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $phieu_san_xuat_id)->get();
            $total_price = 0;
            foreach ($chiTiets as $chiTiet) {
                $total_price += $chiTiet->so_tien;
            }

            $don_gia = $total_price / $khoi_luong;
        }

        $nguyenLieuSanXuat->ten_nguyen_lieu = $ten_nguyen_lieu;
        $nguyenLieuSanXuat->don_gia = $don_gia;
        $nguyenLieuSanXuat->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $nguyenLieuSanXuat->phieu_san_xuat_id = $phieu_san_xuat_id;
        $nguyenLieuSanXuat->khoi_luong = $khoi_luong;
        $nguyenLieuSanXuat->don_vi_tinh = $don_vi_tinh;
        $nguyenLieuSanXuat->mau_sac = $mau_sac ?? '';
        $nguyenLieuSanXuat->mui_thom = $mui_thom ?? '';
        $nguyenLieuSanXuat->chi_tiet_khac = $chi_tiet_khac ?? '';
        $nguyenLieuSanXuat->bao_quan = $bao_quan ?? '';
        $nguyenLieuSanXuat->trang_thai = $trang_thai;
        $nguyenLieuSanXuat->nhan_vien_san_xuat = $nhan_vien_san_xuat;

        if ($oldPhieuSanXuatId != $phieu_san_xuat_id) {
            if ($phieuSanXuat) {
                $phieuSanXuat->khoi_luong_da_dung += $khoi_luong;
                $phieuSanXuat->save();
            }

            $phieuSanXuat = PhieuSanXuat::find($oldPhieuSanXuatId);
            if ($phieuSanXuat) {
                $phieuSanXuat->khoi_luong_da_dung -= $oldKhoiLuong;
                $phieuSanXuat->save();
            }
        } else {
            $phieuSanXuat = PhieuSanXuat::find($phieu_san_xuat_id);
            if ($phieuSanXuat) {
                $phieuSanXuat->khoi_luong_da_dung += $khoi_luong - $oldKhoiLuong;
                $phieuSanXuat->save();
            }
        }
        return $nguyenLieuSanXuat;
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy');
            }

            if ($nguyen_lieu_san_xuat->khoi_luong_da_dung > 0) {
                return redirect()->back()->with('error', 'Không thể xóa nguyên liệu đã dùng');
            }

            $nguyen_lieu_san_xuat->trang_thai = TrangThaiNguyenLieuSanXuat::DELETED();
            $success = $nguyen_lieu_san_xuat->save();

            if ($success) {
                $phieuSanXuat = PhieuSanXuat::find($nguyen_lieu_san_xuat->phieu_san_xuat_id);
                if ($phieuSanXuat) {
                    $phieuSanXuat->khoi_luong_da_dung -= $nguyen_lieu_san_xuat->khoi_luong;
                    $phieuSanXuat->save();
                }
            }

            return redirect()->back()->with('success', 'Đã xoá thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();
            $nguyen_lieu_san_xuat = NguyenLieuSanXuat::find($id);
            if (!$nguyen_lieu_san_xuat || $nguyen_lieu_san_xuat->trang_thai == TrangThaiNguyenLieuSanXuat::DELETED()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy');
            }

            $nguyen_lieu_san_xuat = $this->saveData($nguyen_lieu_san_xuat, $request);
            if (!$nguyen_lieu_san_xuat) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Số lượng không đủ');
            }
            $nguyen_lieu_san_xuat->save();

            DB::commit();
            return redirect()->route('admin.nguyen.lieu.san.xuat.index')->with('success', 'Chỉnh sửa thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
