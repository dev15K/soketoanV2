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
    public function index(Request $request)
    {
        $ngay = $request->input('ngay');
        $nguyen_lieu_tho_id = $request->input('nguyen_lieu_tho_id');

        $queries = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED());

        if ($ngay) {
            $queries->whereDate('ngay', Carbon::parse($ngay)->format('Y-m-d'));
        }

        if ($nguyen_lieu_tho_id) {
            $queries->where('nguyen_lieu_tho_id', $nguyen_lieu_tho_id);
        }

        $datas = $queries->orderByDesc('id')->paginate(20);

        $nlthos = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->get();
        return view('admin.pages.nguyen_lieu_phan_loai.index', compact('datas', 'nlthos', 'ngay', 'nguyen_lieu_tho_id'));
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
            if (!$nguyen_lieu_phan_loai) {
                return redirect()->back()->with('error', 'Khối lượng ban đầu không đủ')->withInput();
            }
            $nguyen_lieu_phan_loai->save();

            $this->updateNguyenLieuTho();

            return redirect()->back()->with('success', 'Thêm mới nguyên liệu phân loại thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function saveData(NguyenLieuPhanLoai $NguyenLieuPhanLoai, Request $request)
    {
        $is_update = false;
        if ($NguyenLieuPhanLoai->nguyen_lieu_tho_id) {
            $is_update = true;
            $old_nguyen_lieu_tho_id = $NguyenLieuPhanLoai->nguyen_lieu_tho_id;
            $old_khoi_luong_ban_dau = $NguyenLieuPhanLoai->khoi_luong_ban_dau;
        }
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');
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
        $trang_thai = $request->input('trang_thai') ?? TrangThaiNguyenLieuPhanLoai::ACTIVE();

        $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();
        $khoi_luong_ban_dau = $nguyenLieuTho->khoi_luong;

        if ($khoi_luong_ban_dau <= 0) {
            return false;
        }

        $NguyenLieuPhanLoai->ten_nguyen_lieu = $ten_nguyen_lieu;
        $NguyenLieuPhanLoai->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $NguyenLieuPhanLoai->nguyen_lieu_tho_id = $nguyen_lieu_tho_id;
        $NguyenLieuPhanLoai->nu_cao_cap = $nu_cao_cap ?? 0;
        $NguyenLieuPhanLoai->nu_vip = $nu_vip ?? 0;
        $NguyenLieuPhanLoai->nhang = $nhang ?? 0;
        $NguyenLieuPhanLoai->vong = $vong ?? 0;
        $NguyenLieuPhanLoai->tam_tre = $tam_tre ?? 0;
        $NguyenLieuPhanLoai->keo = $keo ?? 0;
        $NguyenLieuPhanLoai->nau_dau = $nau_dau ?? 0;
        $NguyenLieuPhanLoai->khoi_luong_ban_dau = $khoi_luong_ban_dau;
        $NguyenLieuPhanLoai->ghi_chu = $ghi_chu;
        $NguyenLieuPhanLoai->trang_thai = $trang_thai;

        $NguyenLieuPhanLoai->tong_khoi_luong = $nu_cao_cap + $nu_vip + $nhang + $vong + $tam_tre + $keo + $nau_dau;

        $cp = compareNumbers($khoi_luong_ban_dau, $NguyenLieuPhanLoai->tong_khoi_luong);
        if ($khoi_luong_ban_dau < $NguyenLieuPhanLoai->tong_khoi_luong) {
            return false;
        }

        if ($is_update) {
            if (isset($old_nguyen_lieu_tho_id) && isset($old_khoi_luong_ban_dau) && $old_nguyen_lieu_tho_id != $nguyen_lieu_tho_id) {
                $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();
                if ($nguyenLieuTho) {
                    $NguyenLieuPhanLoai->chi_phi_mua = $nguyenLieuTho->chi_phi_mua / $nguyenLieuTho->khoi_luong * $khoi_luong_ban_dau;

                    $NguyenLieuPhanLoai->khoi_luong_hao_hut = $khoi_luong_ban_dau - $NguyenLieuPhanLoai->tong_khoi_luong;
                    $NguyenLieuPhanLoai->gia_truoc_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->khoi_luong_ban_dau, 2);
                    $NguyenLieuPhanLoai->gia_sau_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->tong_khoi_luong, 2);

                    $nguyenLieuTho->khoi_luong_da_phan_loai = $khoi_luong_ban_dau;
                    $nguyenLieuTho->save();
                }

                $nguyenLieuTho = NguyenLieuTho::where('id', $old_nguyen_lieu_tho_id)->first();
                if ($nguyenLieuTho) {
                    $nguyenLieuTho->khoi_luong_da_phan_loai = $nguyenLieuTho->khoi_luong_da_phan_loai - $old_khoi_luong_ban_dau;
                    $nguyenLieuTho->save();
                }
            } else {
                $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();

                if ($nguyenLieuTho) {
                    $NguyenLieuPhanLoai->chi_phi_mua = $nguyenLieuTho->chi_phi_mua / $nguyenLieuTho->khoi_luong * $khoi_luong_ban_dau;

                    $NguyenLieuPhanLoai->khoi_luong_hao_hut = $khoi_luong_ban_dau - $NguyenLieuPhanLoai->tong_khoi_luong;
                    $NguyenLieuPhanLoai->gia_truoc_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->khoi_luong_ban_dau, 2);
                    $NguyenLieuPhanLoai->gia_sau_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->tong_khoi_luong, 2);

                    $nguyenLieuTho->khoi_luong_da_phan_loai = $khoi_luong_ban_dau - $old_khoi_luong_ban_dau ?? 0;
                    $nguyenLieuTho->save();
                }
            }
        } else {
            $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_tho_id)->first();
            if ($nguyenLieuTho) {
                $NguyenLieuPhanLoai->chi_phi_mua = $nguyenLieuTho->chi_phi_mua / $nguyenLieuTho->khoi_luong * $khoi_luong_ban_dau;

                $NguyenLieuPhanLoai->khoi_luong_hao_hut = $khoi_luong_ban_dau - $NguyenLieuPhanLoai->tong_khoi_luong;
                $NguyenLieuPhanLoai->gia_truoc_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->khoi_luong_ban_dau, 2);
                $NguyenLieuPhanLoai->gia_sau_phan_loai = round($NguyenLieuPhanLoai->chi_phi_mua / $NguyenLieuPhanLoai->tong_khoi_luong, 2);

                $nguyenLieuTho->khoi_luong_da_phan_loai = $khoi_luong_ban_dau;
                $nguyenLieuTho->save();
            }
        }

        return $NguyenLieuPhanLoai;
    }

    private function updateNguyenLieuTho()
    {
        $datas = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();
        NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
            ->orderByDesc('id')
            ->update(['allow_change' => true]);
        foreach ($datas as $data) {
            $nguyenLieuTho = NguyenLieuTho::where('id', $data->nguyen_lieu_tho_id)->first();
            if ($nguyenLieuTho) {
//                $nguyenLieuTho->khoi_luong = $nguyenLieuTho->khoi_luong - $data->tong_khoi_luong;
                $nguyenLieuTho->allow_change = false;
                $nguyenLieuTho->save();
            }
        }
    }

    public function update($id, Request $request)
    {
        try {
            $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::find($id);
            if (!$nguyen_lieu_phan_loai || $nguyen_lieu_phan_loai->trang_thai == TrangThaiNguyenLieuPhanLoai::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu phân loại');
            }

            $nguyen_lieu_phan_loai = $this->saveData($nguyen_lieu_phan_loai, $request);
            if (!$nguyen_lieu_phan_loai) {
                return redirect()->back()->with('error', 'Khối lượng ban đầu không đủ');
            }
            $nguyen_lieu_phan_loai->save();

            $this->updateNguyenLieuTho();

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

            NguyenLieuPhanLoai::where('id', $id)
                ->where('khoi_luong_da_phan_loai', null)
                ->orWhere('khoi_luong_da_phan_loai', 0)
                ->update(['trang_thai' => TrangThaiNguyenLieuPhanLoai::DELETED()]);

            $nguyenLieuTho = NguyenLieuTho::where('id', $nguyen_lieu_phan_loai->nguyen_lieu_tho_id)->first();
            if ($nguyenLieuTho) {
                $nguyenLieuTho->khoi_luong_da_phan_loai = $nguyenLieuTho->khoi_luong_da_phan_loai - $nguyen_lieu_phan_loai->khoi_luong_ban_dau;
                $nguyenLieuTho->save();

                $otherNguyenLieuTho = NguyenLieuPhanLoai::where('nguyen_lieu_tho_id', $nguyenLieuTho->id)
                    ->where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
                    ->first();

                if (!$otherNguyenLieuTho) {
                    $nguyenLieuTho->allow_change = true;
                    $nguyenLieuTho->save();
                }
            }

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu phân loại thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
