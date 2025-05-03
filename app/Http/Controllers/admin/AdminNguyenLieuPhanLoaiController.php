<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuTho;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminNguyenLieuPhanLoaiController extends Controller
{
    public function index()
    {
        $datas = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->paginate(20);

        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.nguyen_lieu_phan_loai.index', compact('datas', 'nlthos'));
    }

    public function detail($id)
    {
        $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::find($id);
        if (!$nguyen_lieu_phan_loai || $nguyen_lieu_phan_loai->trang_thai == TrangThaiNguyenLieuPhanLoai::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu phân loại');
        }

        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.nguyen_lieu_phan_loai.detail', compact('nguyen_lieu_phan_loai', 'nlthos'));
    }

    public function store(Request $request)
    {
        try {
            $nguyen_lieu_phan_loai = new NguyenLieuPhanLoai();

            $nguyen_lieu_phan_loai = $this->saveData($nguyen_lieu_phan_loai, $request);
            $nguyen_lieu_phan_loai->save();

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu phân loại thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(NguyenLieuPhanLoai $NguyenLieuPhanLoai, Request $request)
    {
        $ngay = $request->input('ngay');
        $nguyen_lieu_tho_id = $request->input('nguyen_lieu_tho_id');
        $nu_cao_cap = $request->input('nu_cao_cap');
        $nu_vip = $request->input('nu_vip');
        $nhang = $request->input('nhang');
        $vong = $request->input('vong');
        $tam_tre = $request->input('tam_tre');
        $keo = $request->input('keo');
        $nau_dau = $request->input('nau_dau');
        $ghi_chu = $request->input('ghi_chu');
        $trang_thai = $request->input('trang_thai');

        $NguyenLieuPhanLoai->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $NguyenLieuPhanLoai->nguyen_lieu_tho_id = $nguyen_lieu_tho_id;
        $NguyenLieuPhanLoai->nu_cao_cap = $nu_cao_cap;
        $NguyenLieuPhanLoai->nu_vip = $nu_vip;
        $NguyenLieuPhanLoai->nhang = $nhang;
        $NguyenLieuPhanLoai->vong = $vong;
        $NguyenLieuPhanLoai->tam_tre = $tam_tre;
        $NguyenLieuPhanLoai->keo = $keo;
        $NguyenLieuPhanLoai->nau_dau = $nau_dau;

        $NguyenLieuPhanLoai->ghi_chu = $ghi_chu;
        $NguyenLieuPhanLoai->trang_thai = $trang_thai;

        $NguyenLieuPhanLoai->tong_khoi_luong = $nu_cao_cap + $nu_vip + $nhang + $vong + $tam_tre + $keo + $nau_dau;

        $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();
        if ($nguyenLieuTho) {
            $NguyenLieuPhanLoai->khoi_luong_ban_dau = $nguyenLieuTho->khoi_luong;
            $NguyenLieuPhanLoai->chi_phi_mua = $nguyenLieuTho->chi_phi_mua;


            $NguyenLieuPhanLoai->khoi_luong_hao_hut = $nguyenLieuTho->khoi_luong - $NguyenLieuPhanLoai->tong_khoi_luong;

            $NguyenLieuPhanLoai->gia_sau_phan_loai = round($nguyenLieuTho->chi_phi_mua / $NguyenLieuPhanLoai->tong_khoi_luong, 2);
        }

        return $NguyenLieuPhanLoai;
    }

    public function update($id, Request $request)
    {
        try {
            $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::find($id);
            if (!$nguyen_lieu_phan_loai || $nguyen_lieu_phan_loai->trang_thai == TrangThaiNguyenLieuPhanLoai::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu phân loại');
            }

            $nguyen_lieu_phan_loai = $this->saveData($nguyen_lieu_phan_loai, $request);
            $nguyen_lieu_phan_loai->save();

            return redirect()->route('admin.nguyen.lieu.phan.loai.index')->with('success', 'Chỉnh sửa nguyên liệu phân loại thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::find($id);
            if (!$nguyen_lieu_phan_loai || $nguyen_lieu_phan_loai->trang_thai == TrangThaiNguyenLieuPhanLoai::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu phân loại');
            }

            $nguyen_lieu_phan_loai->trang_thai = TrangThaiNguyenLieuPhanLoai::DELETED();
            $nguyen_lieu_phan_loai->save();

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu phân loại thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
