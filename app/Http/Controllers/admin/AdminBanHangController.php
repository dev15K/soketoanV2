<?php

namespace App\Http\Controllers\admin;

use App\Enums\LoaiSanPham;
use App\Enums\TrangThaiBanHang;
use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuThanhPham;
use App\Enums\TrangThaiNguyenLieuTho;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Http\Controllers\Controller;
use App\Models\BanHang;
use App\Models\BanHangChiTiet;
use App\Models\KhachHang;
use App\Models\LoaiQuy;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuSanXuat;
use App\Models\NguyenLieuThanhPham;
use App\Models\NguyenLieuTho;
use App\Models\NguyenLieuTinh;
use App\Models\SoQuy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBanHangController extends Controller
{
    public function index()
    {
        $datas = BanHang::where('trang_thai', '!=', TrangThaiBanHang::DELETED())
            ->orderByDesc('id')
            ->paginate(10);

        $khachhangs = KhachHang::where('trang_thai', '!=', TrangThaiBanHang::DELETED())
            ->orderByDesc('id')
            ->get();

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.ban_hang.index', compact('datas', 'khachhangs', 'loai_quies'));
    }

    public function detail($id)
    {
        $banhang = BanHang::find($id);
        if (!$banhang || $banhang->trang_thai == TrangThaiBanHang::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
        }

        $khachhangs = KhachHang::where('trang_thai', '!=', TrangThaiBanHang::DELETED())
            ->orderByDesc('id')
            ->get();

        $chiTietBanHangs = BanHangChiTiet::where('ban_hang_id', $id)
            ->orderByDesc('id')
            ->get();

        switch ($banhang->loai_san_pham) {
            case LoaiSanPham::NGUYEN_LIEU_THO():
                $nguyenlieus = NguyenLieuTho::where('trang_thai', '!=', TrangThaiNguyenLieuTho::DELETED())
                    ->orderByDesc('id')
                    ->get();
                break;
            case LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                $nguyenlieus = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
                    ->orderByDesc('id')
                    ->get();
                break;
            case LoaiSanPham::NGUYEN_LIEU_TINH():
                $nguyenlieus = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED())
                    ->orderByDesc('id')
                    ->get();
                break;
            case LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                $nguyenlieus = [];
                break;
            case LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                $nguyenlieus = NguyenLieuThanhPham::where('trang_thai', '!=', TrangThaiNguyenLieuThanhPham::DELETED())
                    ->orderByDesc('id')
                    ->get();
                break;
        }

        $loai_quies = LoaiQuy::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.ban_hang.detail', compact('banhang', 'chiTietBanHangs', 'khachhangs', 'nguyenlieus', 'loai_quies'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $khach_hang_id = $request->input('khach_hang_id');
            $ten_khach_hang = $request->input('ten_khach_hang');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $dia_chi = $request->input('dia_chi');
            $loai_san_pham = $request->input('loai_san_pham');
            $da_thanht_toan = $request->input('da_thanht_toan');
            $loai_quy_id = $request->input('loai_quy_id');

            $loaiQuy = LoaiQuy::find($loai_quy_id);
            if (!$loaiQuy) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy loại quý');
            }

            $banhang = new BanHang();

            $banhang->khach_hang_id = $khach_hang_id != 0 ? $khach_hang_id : null;
            $banhang->ban_le = $khach_hang_id == 0;
            $banhang->khach_le = $ten_khach_hang;
            $banhang->so_dien_thoai = $so_dien_thoai;
            $banhang->dia_chi = $dia_chi;
            $banhang->loai_san_pham = $loai_san_pham;
            $banhang->phuong_thuc_thanh_toan = $loai_quy_id;
            $banhang->tong_tien = 0;
            $banhang->da_thanht_toan = $da_thanht_toan ?? 0;
            $banhang->cong_no = 0;
            $banhang->trang_thai = TrangThaiBanHang::ACTIVE();

            $banhang->save();

            $id = $banhang->id;

            $san_pham_ids = $request->input('san_pham_id');
            $gia_bans = $request->input('gia_bans');
            $so_luongs = $request->input('so_luong');

            $total = 0;
            for ($i = 0; $i < count($san_pham_ids); $i++) {
                $san_pham_id = $san_pham_ids[$i];
                $ban_hang_chi_tiet = new BanHangChiTiet();
                $ban_hang_chi_tiet->ban_hang_id = $id;
                $ban_hang_chi_tiet->san_pham_id = $san_pham_id;
                $ban_hang_chi_tiet->gia_ban = $gia_bans[$i];
                $ban_hang_chi_tiet->so_luong = $so_luongs[$i];
                $ban_hang_chi_tiet->tong_tien = $ban_hang_chi_tiet->gia_ban * $ban_hang_chi_tiet->so_luong;
                $ban_hang_chi_tiet->save();

                switch ($banhang->loai_san_pham) {
                    case LoaiSanPham::NGUYEN_LIEU_THO():
                        $nguyenLieuTho = NguyenLieuTho::find($san_pham_id);
                        if ($nguyenLieuTho) {
                            $kl = $nguyenLieuTho->khoi_luong - $nguyenLieuTho->khoi_luong_da_ban;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuTho->khoi_luong_da_ban = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuTho->save();
                        }
                        break;
                    case LoaiSanPham::NGUYEN_LIEU_PHAN_LOAI():
                        $nguyenLieuPhanLoai = NguyenLieuPhanLoai::find($san_pham_id);
                        if ($nguyenLieuPhanLoai) {
                            $kl = $nguyenLieuPhanLoai->tong_khoi_luong - $nguyenLieuPhanLoai->khoi_luong_da_phan_loai;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuPhanLoai->khoi_luong_da_phan_loai = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuPhanLoai->save();
                        }
                        break;
                    case LoaiSanPham::NGUYEN_LIEU_TINH():
                        $nguyenLieuTinh = NguyenLieuTinh::find($san_pham_id);
                        if ($nguyenLieuTinh) {
                            $kl = $nguyenLieuTinh->tong_khoi_luong - $nguyenLieuTinh->so_luong_da_dung;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuTinh->so_luong_da_dung = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuTinh->save();
                        }
                        break;
                    case LoaiSanPham::NGUYEN_LIEU_SAN_XUAT():
                        $nguyenLieuSanXuat = NguyenLieuSanXuat::find($san_pham_id);
                        if ($nguyenLieuSanXuat) {
                            $kl = $nguyenLieuSanXuat->khoi_luong - $nguyenLieuSanXuat->khoi_luong_da_dung;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuSanXuat->khoi_luong_da_dung = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuSanXuat->save();
                        }
                        break;
                    case LoaiSanPham::NGUYEN_LIEU_THANH_PHAM():
                        $nguyenLieuThanhPham = NguyenLieuThanhPham::find($san_pham_id);
                        if ($nguyenLieuThanhPham) {
                            $kl = $nguyenLieuThanhPham->so_luong - $nguyenLieuThanhPham->so_luong_da_ban;
                            if ($kl > $ban_hang_chi_tiet->so_luong) {
                                $banhang->delete();
                                DB::rollBack();
                                return redirect()->back()->with('error', 'Số lượng không đủ!');
                            }
                            $nguyenLieuThanhPham->so_luong_da_ban = $ban_hang_chi_tiet->so_luong;
                            $nguyenLieuThanhPham->save();
                        }
                        break;
                }

                $total += $ban_hang_chi_tiet->gia_ban * $ban_hang_chi_tiet->so_luong;
            }

            $banhang->tong_tien = $total;
            $banhang->da_thanht_toan = $da_thanht_toan;
            $banhang->cong_no = $total - $da_thanht_toan;
            $banhang->save();

            $this->insertBanHang($banhang, false, null, $loai_quy_id);

            DB::commit();
            return redirect()->back()->with('success', 'Thêm mới hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function insertBanHang(BanHang $banhang, $isUpdate = false, $idUpdate = null, $loai_quy_id)
    {
        if (!$isUpdate) {
            $code = $this->generateCode();
            $soquy = new SoQuy();
            $soquy->loai = 1;
            $soquy->so_tien = $banhang->da_thanht_toan;
            $soquy->gia_tri_id = $banhang->id;
            $soquy->ngay = Carbon::now();
            $soquy->noi_dung = 'Phiếu thu bán hàng cho đơn hàng: #' . $banhang->id;
            $soquy->ma_phieu = $code;
            $soquy->loai_quy_id = $loai_quy_id;
            $soquy->save();

            $loaiQuy = LoaiQuy::find($loai_quy_id);
            if ($loaiQuy) {
                $loaiQuy->tong_tien_quy = $loaiQuy->tong_tien_quy + $banhang->da_thanht_toan;
                $loaiQuy->save();
            }
        } else {
            $soquy = SoQuy::find($idUpdate);
            if ($loai_quy_id != $soquy->loai_quy_id) {
                $loaiQuy = LoaiQuy::find($soquy->loai_quy_id);
                if ($loaiQuy) {
                    $loaiQuy->tong_tien_quy = $loaiQuy->tong_tien_quy - $soquy->so_tien;
                    $loaiQuy->save();
                }

                $soquy->delete();

                $code = $this->generateCode();
                $soquy = new SoQuy();
                $soquy->loai = 1;
                $soquy->so_tien = $banhang->da_thanht_toan;
                $soquy->gia_tri_id = $banhang->id;
                $soquy->ngay = Carbon::now();
                $soquy->noi_dung = 'Phiếu thu bán hàng cho đơn hàng: #' . $banhang->id;
                $soquy->ma_phieu = $code;
                $soquy->loai_quy_id = $loai_quy_id;
                $soquy->save();

                $loaiQuy = LoaiQuy::find($loai_quy_id);
                if ($loaiQuy) {
                    $loaiQuy->tong_tien_quy = $loaiQuy->tong_tien_quy + $banhang->da_thanht_toan;
                    $loaiQuy->save();
                }
            } else {
                $oldTien = $soquy->so_tien;
                $soquy->loai = 1;
                $soquy->so_tien = $banhang->da_thanht_toan;
                $soquy->ngay = Carbon::now();
                $soquy->gia_tri_id = $banhang->id;
                $soquy->noi_dung = 'Phiếu thu bán hàng cho đơn hàng: #' . $banhang->id;
                $soquy->loai_quy_id = $loai_quy_id;
                $soquy->save();

                $loaiQuy = LoaiQuy::find($loai_quy_id);
                if ($loaiQuy) {
                    $loaiQuy->tong_tien_quy = $loaiQuy->tong_tien_quy + $banhang->da_thanht_toan - $oldTien;
                    $loaiQuy->save();
                }
            }
        }
    }

    private function generateCode()
    {
        $lastItem = SoQuy::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $khach_hang_id = $request->input('khach_hang_id');
            $ten_khach_hang = $request->input('ten_khach_hang');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $dia_chi = $request->input('dia_chi');
            $loai_san_pham = $request->input('loai_san_pham');
            $da_thanht_toan = $request->input('da_thanht_toan');
            $loai_quy_id = $request->input('loai_quy_id');

            $banhang = BanHang::find($id);
            if (!$banhang || $banhang->trang_thai == TrangThaiBanHang::DELETED()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
            }

            $banhang->khach_hang_id = $khach_hang_id != 0 ? $khach_hang_id : null;
            $banhang->ban_le = $khach_hang_id == 0;
            $banhang->khach_le = $ten_khach_hang;
            $banhang->so_dien_thoai = $so_dien_thoai;
            $banhang->dia_chi = $dia_chi;
            $banhang->loai_san_pham = $loai_san_pham;
            $banhang->phuong_thuc_thanh_toan = $loai_quy_id;

            $san_pham_ids = $request->input('san_pham_id');
            $gia_bans = $request->input('gia_bans');
            $so_luongs = $request->input('so_luong');

            BanHangChiTiet::where('ban_hang_id', $id)
                ->whereNotIn('san_pham_id', $san_pham_ids)
                ->delete();

            $total = 0;
            for ($i = 0; $i < count($san_pham_ids); $i++) {
                $san_pham_id = $san_pham_ids[$i];

                $oldData = BanHangChiTiet::where('ban_hang_id', $id)
                    ->where('san_pham_id', $san_pham_id)
                    ->first();

                if ($oldData) {
                    $ban_hang_chi_tiet = $oldData;
                } else {
                    $ban_hang_chi_tiet = new BanHangChiTiet();
                }

                $ban_hang_chi_tiet->ban_hang_id = $id;
                $ban_hang_chi_tiet->san_pham_id = $san_pham_id;
                $ban_hang_chi_tiet->gia_ban = $gia_bans[$i];
                $ban_hang_chi_tiet->so_luong = $so_luongs[$i];
                $ban_hang_chi_tiet->tong_tien = $ban_hang_chi_tiet->gia_ban * $ban_hang_chi_tiet->so_luong;
                $ban_hang_chi_tiet->save();

                $total += $ban_hang_chi_tiet->gia_ban * $ban_hang_chi_tiet->so_luong;
            }

            $banhang->tong_tien = $total;
            $banhang->da_thanht_toan = $da_thanht_toan;
            $banhang->cong_no = $total - $da_thanht_toan;
            $banhang->save();

            $idUpdate = SoQuy::where('gia_tri_id', $banhang->id)->first();
            $this->insertBanHang($banhang, true, $idUpdate->id, $loai_quy_id);

            DB::commit();
            return redirect()->route('admin.ban.hang.index')->with('success', 'Chỉnh sửa hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $banhang = BanHang::find($id);
            if (!$banhang || $banhang->trang_thai == TrangThaiBanHang::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy hóa đơn bán hàng');
            }

            $banhang->trang_thai = TrangThaiBanHang::DELETED();
            $banhang->save();

            SoQuy::where('gia_tri_id', $banhang->id)
                ->where('loai', 1)
                ->delete();

            return redirect()->back()->with('success', 'Đã xoá hóa đơn bán hàng thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
