<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuTho;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuTho;
use App\Models\NhaCungCaps;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminNguyenLieuThoController extends Controller
{
    public function index()
    {
        $datas = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->paginate(20);

        $nccs = NhaCungCaps::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.nguyen_lieu_tho.index', compact('datas', 'nccs'));
    }

    public function detail($id)
    {
        $nguyen_lieu_tho = NguyenLieuTho::find($id);
        if (!$nguyen_lieu_tho || $nguyen_lieu_tho->trang_thai == TrangThaiNguyenLieuTho::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thô');
        }

        $nccs = NhaCungCaps::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.nguyen_lieu_tho.detail', compact('nguyen_lieu_tho', 'nccs'));
    }

    public function store(Request $request)
    {
        try {
            $nguyen_lieu_tho = new NguyenLieuTho();

            $nguyen_lieu_tho = $this->saveData($nguyen_lieu_tho, $request);
            $nguyen_lieu_tho->save();

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu thô thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(NguyenLieuTho $nguyenLieuTho, Request $request)
    {
        $ngay = $request->input('ngay');
        $nha_cung_cap_id = $request->input('nha_cung_cap_id');
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');
        $loai = $request->input('loai');
        $nguon_goc = $request->input('nguon_goc');
        $khoi_luong = $request->input('khoi_luong');
        $kich_thuoc = $request->input('kich_thuoc');
        $do_kho = $request->input('do_kho');
        $dieu_kien_luu_tru = $request->input('dieu_kien_luu_tru');
        $chi_phi_mua = $request->input('chi_phi_mua');
        $phuong_thuc_thanh_toan = $request->input('phuong_thuc_thanh_toan');
        $cong_no = $request->input('cong_no');
        $nhan_su_xu_li = $request->input('nhan_su_xu_li');
        $thoi_gian_phan_loai = $request->input('thoi_gian_phan_loai');
        $ghi_chu = $request->input('ghi_chu');
        $trang_thai = $request->input('trang_thai');

        $nguyenLieuTho->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $nguyenLieuTho->nha_cung_cap_id = $nha_cung_cap_id;
        $nguyenLieuTho->ten_nguyen_lieu = $ten_nguyen_lieu;
        $nguyenLieuTho->loai = $loai;
        $nguyenLieuTho->nguon_goc = $nguon_goc;
        $nguyenLieuTho->khoi_luong = $khoi_luong;
        $nguyenLieuTho->kich_thuoc = $kich_thuoc;
        $nguyenLieuTho->do_kho = $do_kho;
        $nguyenLieuTho->dieu_kien_luu_tru = $dieu_kien_luu_tru;
        $nguyenLieuTho->chi_phi_mua = $chi_phi_mua;
        $nguyenLieuTho->phuong_thuc_thanh_toan = $phuong_thuc_thanh_toan;
        $nguyenLieuTho->cong_no = $cong_no;
        $nguyenLieuTho->nhan_su_xu_li = $nhan_su_xu_li;
        $nguyenLieuTho->thoi_gian_phan_loai = Carbon::parse($thoi_gian_phan_loai)->format('Y-m-d');
        $nguyenLieuTho->ghi_chu = $ghi_chu;
        $nguyenLieuTho->trang_thai = $trang_thai;

        return $nguyenLieuTho;
    }

    public function update($id, Request $request)
    {
        try {
            $nguyen_lieu_tho = NguyenLieuTho::find($id);
            if (!$nguyen_lieu_tho || $nguyen_lieu_tho->trang_thai == TrangThaiNguyenLieuTho::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thô');
            }

            $nguyen_lieu_tho = $this->saveData($nguyen_lieu_tho, $request);
            $nguyen_lieu_tho->save();

            return redirect()->route('admin.nguyen.lieu.tho.index')->with('success', 'Chỉnh sửa nguyên liệu thô thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_tho = NguyenLieuTho::find($id);
            if (!$nguyen_lieu_tho || $nguyen_lieu_tho->trang_thai == TrangThaiNguyenLieuTho::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu thô');
            }

            $nguyen_lieu_tho->trang_thai = TrangThaiNguyenLieuTho::DELETED();
            $nguyen_lieu_tho->save();

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu thô thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
