<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiBanHang;
use App\Enums\TrangThaiKhachHang;
use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuSanXuat;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Enums\TrangThaiNhaCungCap;
use App\Enums\TrangThaiPhieuSanXuat;
use App\Enums\TrangThaiSanPham;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\BanHang;
use App\Models\KhachHang;
use App\Models\LoaiQuy;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use App\Models\NguyenLieuTho;
use App\Models\NguyenLieuTinh;
use App\Models\NguyenLieuTinhChiTiet;
use App\Models\NhaCungCaps;
use App\Models\PhieuSanXuat;
use App\Models\PhieuSanXuatChiTiet;
use App\Models\SanPham;
use App\Models\SoQuy;
use App\Models\ThongTin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminHomeController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $loai_quy_search = $request->input('loai_quy_search');

        $datas = SoQuy::where('deleted_at', null)
            ->when($start_date, function ($query) use ($start_date) {
                return $query->whereDate('created_at', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->whereDate('created_at', '<=', $end_date);
            })
            ->when($loai_quy_search, function ($query) use ($loai_quy_search) {
                return $query->where('loai_quy_id', $loai_quy_search);
            })
            ->orderByDesc('id')
            ->paginate(10);

        $old_query = SoQuy::whereNull('deleted_at');

        if ($start_date) {
            $old_query->whereDate('created_at', '<', $start_date);
        } elseif ($end_date) {
            $old_query->whereDate('created_at', '<', $end_date);
        } else {
            $old_query->whereDate('created_at', '<', Carbon::today());
        }

        $old_datas = $old_query->orderByDesc('id')->get();

        $ton_dau = 0;
        foreach ($old_datas as $old_data) {
            if ($old_data->loai == 1) {
                $ton_dau += $old_data->so_tien;
            } else {
                $ton_dau -= $old_data->so_tien;
            }
        }

        $query = SoQuy::whereNull('deleted_at');

        if ($start_date && $end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        } elseif ($start_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($start_date)->endOfDay()
            ]);
        } elseif ($end_date) {
            $query->whereBetween('created_at', [
                Carbon::today(),
                Carbon::parse($end_date)->endOfDay()
            ]);
        } else {
            $query->whereBetween('created_at', [
                Carbon::today(),
                Carbon::today()->endOfDay()
            ]);
        }

        $new_datas = $query->orderByDesc('id')->get();

        $ton_cuoi = 0;
        foreach ($new_datas as $new_data) {
            if ($new_data->loai == 1) {
                $ton_cuoi += $new_data->so_tien;
            } else {
                $ton_cuoi -= $new_data->so_tien;
            }
        }

        $ma_phieu = $this->generateCode();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.index', compact('datas', 'ton_dau', 'ton_cuoi', 'ma_phieu', 'start_date', 'end_date', 'loai_quies', 'loai_quy_search'));
    }


    public function deleteItem(Request $request)
    {
        try {
            $list_id = $request->input('list_id');
            $type = $request->input('type');

            switch ($type) {
                case "tho":
                    NguyenLieuTho::whereIn('id', $list_id)
                        ->where(function ($query) {
                            $query->whereNull('khoi_luong_da_phan_loai')
                                ->orWhere('khoi_luong_da_phan_loai', 0);
                        })
                        ->update(['trang_thai' => TrangThaiNguyenLieuTho::DELETED()]);

                    break;
                case "phan_loai":
                    NguyenLieuPhanLoai::whereIn('id', $list_id)
                        ->where(function ($query) {
                            $query->whereNull('khoi_luong_da_phan_loai')
                                ->orWhere('khoi_luong_da_phan_loai', 0);
                        })
                        ->update(['trang_thai' => TrangThaiNguyenLieuPhanLoai::DELETED()]);

                    foreach ($list_id as $id) {
                        $nguyen_lieu_phan_loai = NguyenLieuPhanLoai::where('id', $id)
                            ->where(function ($query) {
                                $query->whereNull('khoi_luong_da_phan_loai')
                                    ->orWhere('khoi_luong_da_phan_loai', 0);
                            })
                            ->first();

                        if ($nguyen_lieu_phan_loai) {
                            $nguyenLieuTho = NguyenLieuTho::where('id', $id)->first();
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
                        }
                    }
                    break;
                case "tinh":
                    NguyenLieuTinh::whereIn('id', $list_id)
                        ->where(function ($query) {
                            $query->whereNull('so_luong_da_dung')
                                ->orWhere('so_luong_da_dung', 0);
                        })
                        ->update(['trang_thai' => TrangThaiNguyenLieuTinh::DELETED()]);

                    foreach ($list_id as $id) {
                        $nguyenLieuTinh = NguyenLieuTinh::where('id', $id)
                            ->where(function ($query) {
                                $query->whereNull('so_luong_da_dung')
                                    ->orWhere('so_luong_da_dung', 0);
                            })
                            ->first();
                        if ($nguyenLieuTinh) {
                            $chiTiets = NguyenLieuTinhChiTiet::where('nguyen_lieu_tinh_id', $id)->get();
                            foreach ($chiTiets as $chiTiet) {
                                $nguyenLieuPhanLoai = NguyenLieuPhanLoai::find($chiTiet->nguyen_lieu_phan_loai_id);
                                if ($nguyenLieuPhanLoai) {
                                    $mapping = [
                                        'Nguyên liệu nụ cao cấp (NCC)' => 'nu_cao_cap',
                                        'Nguyên liệu nụ VIP (NVIP)' => 'nu_vip',
                                        'Nguyên liệu nhang (NLN)' => 'nhang',
                                        'Nguyên liệu vòng (NLV)' => 'vong',
                                        'Tăm dài' => 'tam_dai',
                                        'Tăm ngắn' => 'tam_ngan',
                                        'Nước cất' => 'nuoc_cat',
                                        'Keo' => 'keo',
                                        'Nấu dầu' => 'nau_dau',
                                        'Tăm nhanh sào' => 'tam_nhanh_sao',
                                    ];

                                    $ten = $chiTiet->ten_nguyen_lieu;

                                    if (isset($mapping[$ten])) {
                                        $field = $mapping[$ten];
                                        $nguyenLieuPhanLoai->$field += $chiTiet->khoi_luong;
                                    }

                                    $nguyenLieuPhanLoai->khoi_luong_da_phan_loai -= $chiTiet->khoi_luong;
                                    $nguyenLieuPhanLoai->save();
                                }
                            }
                        }
                    }
                    break;
                case "phieu_san_xuat":
//                    PhieuSanXuat::whereIn('id', $list_id)
//                        ->where(function ($query) {
//                            $query->whereNull('khoi_luong_da_dung')
//                                ->orWhere('khoi_luong_da_dung', 0);
//                        })
//                        ->update(['trang_thai' => TrangThaiPhieuSanXuat::DELETED()]);

                    foreach ($list_id as $id) {
                        $phieuSanXuat = PhieuSanXuat::where('id', $id)
                            ->first();
                        if ($phieuSanXuat) {
                            if (!$phieuSanXuat->khoi_luong_da_dung || $phieuSanXuat->khoi_luong_da_dung == 0) {
                                $phieuSanXuat->trang_thai = TrangThaiPhieuSanXuat::DELETED();
                                $phieuSanXuat->save();
                                $phieuSanXuatChiTiets = PhieuSanXuatChiTiet::where('phieu_san_xuat_id', $id)->get();
                                foreach ($phieuSanXuatChiTiets as $phieuSanXuatChiTiet) {
                                    $nguyenLieuTinh = NguyenLieuTinh::find($phieuSanXuatChiTiet->nguyen_lieu_id);
                                    $nguyenLieuTinh->so_luong_da_dung -= $phieuSanXuatChiTiet->khoi_luong;
                                    $nguyenLieuTinh->save();
                                }
                            }
                        }
                    }
                    break;
                case "thanh_pham":
                    NguyenLieuSanXuat::whereIn('id', $list_id)
                        ->where(function ($query) {
                            $query->whereNull('khoi_luong_da_dung')
                                ->orWhere('khoi_luong_da_dung', 0);
                        })
                        ->update(['trang_thai' => TrangThaiNguyenLieuSanXuat::DELETED()]);

                    break;
                case "dong_goi":
                    foreach ($list_id as $id) {
                        $nguyenLieuThanhPham = NguyenLieuThanhPham::find($id);
                        if (!$nguyenLieuThanhPham || $nguyenLieuThanhPham->trang_thai == TrangThaiNguyenLieuThanhPham::DELETED()) {
                            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tính phần');
                        }

                        $nguyenLieuThanhPham->trang_thai = TrangThaiNguyenLieuThanhPham::DELETED();
                        $nguyenLieuThanhPham->save();

                        $sanPham = SanPham::find($nguyenLieuThanhPham->san_pham_id);
                        if ($sanPham) {
                            $sanPham->ton_kho = $sanPham->ton_kho - $nguyenLieuThanhPham->so_luong;
                            $sanPham->save();
                        }

                        $nguyenLieuSanXuat = NguyenLieuSanXuat::find($nguyenLieuThanhPham->nguyen_lieu_san_xuat_id);
                        if ($nguyenLieuSanXuat) {
                            $nguyenLieuSanXuat->khoi_luong_da_dung -= $nguyenLieuThanhPham->khoi_luong_da_dung;
                            $nguyenLieuSanXuat->save();
                        }
                    }
                    break;
                case "san_pham":
                    SanPham::whereIn('id', $list_id)
                        ->update(['trang_thai' => TrangThaiSanPham::DELETED()]);
                    break;
                case "ban_hang":
                    BanHang::whereIn('id', $list_id)
                        ->update(['trang_thai' => TrangThaiBanHang::DELETED()]);
                    break;
                case "nha_cung_cap":
                    NhaCungCaps::whereIn('id', $list_id)
                        ->update(['trang_thai' => TrangThaiNhaCungCap::DELETED()]);
                    break;
                case "khach_hang":
                    KhachHang::whereIn('id', $list_id)
                        ->update(['trang_thai' => TrangThaiKhachHang::DELETED()]);
                    break;
                case "user":
                    User::whereIn('id', $list_id)
                        ->update(['trang_thai' => UserStatus::DELETED()]);
                    break;
                case "thong_tin":
                    ThongTin::whereIn('id', $list_id)->delete();
                    break;
                case "loai_quy":
                    LoaiQuy::whereIn('id', $list_id)
                        ->where('tong_tien_quy', 0)
                        ->delete();
                    break;
            }
            $data = returnMessage(1, '', 'Success, delete successfully!');
            return response()->json($data)->setStatusCode(200);
        } catch (\Exception $e) {
            $data = returnMessage(0, null, 'Error, please try again!');
            return response()->json($data)->setStatusCode(400);
        }
    }

    private function generateCode()
    {
        $lastItem = SoQuy::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }
}
