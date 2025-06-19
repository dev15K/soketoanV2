<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNguyenLieuPhanLoai;
use App\Enums\TrangThaiNguyenLieuTinh;
use App\Http\Controllers\Controller;
use App\Models\NguyenLieuPhanLoai;
use App\Models\NguyenLieuTinh;
use App\Models\NguyenLieuTinhChiTiet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminNguyenLieuTinhController extends Controller
{
    public function index(Request $request)
    {
        $ngay = $request->input('ngay');
        $code_search = $request->input('code');

        $queries = NguyenLieuTinh::where('trang_thai', '!=', TrangThaiNguyenLieuTinh::DELETED());

        if ($ngay) {
            $queries->whereDate('ngay', Carbon::parse($ngay)->format('Y-m-d'));
        }

        if ($code_search) {
            $queries->where('code', 'like', '%' . $code_search . '%');
        }

        $datas = $queries->orderByDesc('id')->paginate(10);;

        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        $code = $this->generateLHXCode();

        $ma_phieu = $this->generateMaPhieu();


        return view('admin.pages.nguyen_lieu_tinh.index', compact('datas', 'nlphanloais', 'code', 'ma_phieu', 'ngay', 'code_search'));
    }

    private function generateMaPhieu()
    {
        $lastItem = NguyenLieuTinh::orderByDesc('id')
            ->first();

        $lastId = $lastItem?->id;
        return convertNumber($lastId + 1);
    }

    private function generateLHXCode()
    {
        $lastItem = NguyenLieuTinh::orderByDesc('id')->first();

        $lastId = $lastItem?->id;
        return generateLHXCode($lastId + 1);
    }

    public function detail($id)
    {
        $nguyen_lieu_tinh = NguyenLieuTinh::find($id);
        if (!$nguyen_lieu_tinh || $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTinh::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
        }

        $nlphanloais = NguyenLieuPhanLoai::where('trang_thai', '!=', TrangThaiNguyenLieuPhanLoai::DELETED())
            ->orderByDesc('id')
            ->get();

        $dsNLTChiTiet = NguyenLieuTinhChiTiet::where('nguyen_lieu_tinh_id', $id)
            ->orderByDesc('id')
            ->get();

        $code = $nguyen_lieu_tinh->code;
        if (!$nguyen_lieu_tinh->code) {
            $code = $this->generateLHXCode();
        }

        $ma_phieu = $nguyen_lieu_tinh->ma_phieu;
        if (!$nguyen_lieu_tinh->ma_phieu) {
            $ma_phieu = $this->generateMaPhieu();
        }

        return view('admin.pages.nguyen_lieu_tinh.detail', compact('nguyen_lieu_tinh', 'nlphanloais', 'dsNLTChiTiet', 'code', 'ma_phieu'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $nguyen_lieu_tinh = new NguyenLieuTinh();

            $nguyen_lieu_tinh = $this->saveData($nguyen_lieu_tinh, $request);
            $nguyen_lieu_tinh->save();

            $success = $this->saveDataChiTiet($nguyen_lieu_tinh, $request);

            if (!$success) {
                NguyenLieuTinh::where('id', $nguyen_lieu_tinh->id)->delete();
                DB::rollBack();
                return redirect()->route('admin.nguyen.lieu.tinh.index')->with('error', 'Không có đủ nguyên liệu.')->withInput();
            }

            DB::commit();
            return redirect()->back()->with('success', 'Thêm mới nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function saveData(NguyenLieuTinh $NguyenLieuTinh, Request $request)
    {
        $ngay = $request->input('ngay');
        $code = $request->input('code');
        $ma_phieu = $request->input('ma_phieu');
        $ten_nguyen_lieu = $request->input('ten_nguyen_lieu');

        $tong_khoi_luong = 0;
        $gia_tien = 0;

        if (!$NguyenLieuTinh->code) {
            if (!$code) {
                do {
                    $code = generateRandomString(8);
                } while (NguyenLieuTinh::where('code', $code)->where('id', '!=', $NguyenLieuTinh->id)->exists());
            }

            $NguyenLieuTinh->code = $code;
        }

        if (!$NguyenLieuTinh->ma_phieu) {
            if (!$ma_phieu) {
                do {
                    $ma_phieu = generateRandomString(8);
                } while (NguyenLieuTinh::where('ma_phieu', $ma_phieu)->where('id', '!=', $NguyenLieuTinh->id)->exists());
            }

            $NguyenLieuTinh->ma_phieu = $ma_phieu;
        }

        $trang_thai = $request->input('trang_thai');

        $NguyenLieuTinh->ten_nguyen_lieu = $ten_nguyen_lieu;
        $NguyenLieuTinh->ngay = Carbon::parse($ngay)->format('Y-m-d');
        $NguyenLieuTinh->trang_thai = $trang_thai;

        $NguyenLieuTinh->tong_khoi_luong = $tong_khoi_luong;
        $NguyenLieuTinh->gia_tien = $gia_tien;

        return $NguyenLieuTinh;
    }

    private function saveDataChiTiet(NguyenLieuTinh $NguyenLieuTinh, Request $request)
    {
        $nguyen_lieu_phan_loai_ids = $request->input('nguyen_lieu_phan_loai_ids');
        $ten_nguyen_lieus = $request->input('ten_nguyen_lieus');
        $khoi_luongs = $request->input('khoi_luongs');

        $tong_khoi_luong = 0;
        $gia_tien = 0;

        $oldNguyenLieuTinhChiTiets = NguyenLieuTinhChiTiet::where('nguyen_lieu_tinh_id', $NguyenLieuTinh->id)
            ->get();

        foreach ($oldNguyenLieuTinhChiTiets as $oldData) {
            $nguyenLieuPhanLoai = NguyenLieuPhanLoai::find($oldData->nguyen_lieu_phan_loai_id);
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
                ];
                $ten = $oldData->ten_nguyen_lieu;
                $khoi_luong = $oldData->khoi_luong;
                if (isset($mapping[$ten])) {
                    $field = $mapping[$ten];
                    $nguyenLieuPhanLoai->$field += $khoi_luong;
                }

                $nguyenLieuPhanLoai->khoi_luong_da_phan_loai -= $khoi_luong;
                $nguyenLieuPhanLoai->save();
            }
        }

        NguyenLieuTinhChiTiet::where('nguyen_lieu_tinh_id', $NguyenLieuTinh->id)
            ->delete();

        for ($i = 0; $i < count($nguyen_lieu_phan_loai_ids); $i++) {
            $nguyen_lieu_phan_loai_id = $nguyen_lieu_phan_loai_ids[$i];
            $ten_nguyen_lieu = $ten_nguyen_lieus[$i];
            $khoi_luong = $khoi_luongs[$i];

            $ngyen_lieu_phan_loai = NguyenLieuPhanLoai::find($nguyen_lieu_phan_loai_id);
            $so_tien = $khoi_luong * $ngyen_lieu_phan_loai->gia_sau_phan_loai;

            $NguyenLieuTinhChiTiet = new NguyenLieuTinhChiTiet();

            $NguyenLieuTinhChiTiet->nguyen_lieu_tinh_id = $NguyenLieuTinh->id;
            $NguyenLieuTinhChiTiet->nguyen_lieu_phan_loai_id = $nguyen_lieu_phan_loai_id;
            $NguyenLieuTinhChiTiet->ten_nguyen_lieu = $ten_nguyen_lieu;
            $NguyenLieuTinhChiTiet->khoi_luong = $khoi_luong;
            $NguyenLieuTinhChiTiet->so_tien = $so_tien;

            $nguyenLieuPhanLoai = NguyenLieuPhanLoai::find($nguyen_lieu_phan_loai_id);
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
                ];

                $ten = $NguyenLieuTinhChiTiet->ten_nguyen_lieu;

                if (isset($mapping[$ten])) {
                    $field = $mapping[$ten];
                    if ($nguyenLieuPhanLoai->$field < $khoi_luong) {
                        return false;
                    }
                    $nguyenLieuPhanLoai->$field -= $khoi_luong;
                }

                $nguyenLieuPhanLoai->khoi_luong_da_phan_loai += $khoi_luong;
                $nguyenLieuPhanLoai->save();
            }

            $NguyenLieuTinhChiTiet->save();

            $tong_khoi_luong += $khoi_luong;
            $gia_tien += $so_tien;
        }

        $NguyenLieuTinh->tong_khoi_luong = $tong_khoi_luong;
        $NguyenLieuTinh->gia_tien = $gia_tien;

        $NguyenLieuTinh->save();
        return $NguyenLieuTinh;
    }

    public function delete($id)
    {
        try {
            $nguyen_lieu_tinh = NguyenLieuTinh::find($id);
            if (!$nguyen_lieu_tinh || $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTinh::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
            }

            NguyenLieuTinh::where('id', $id)
                ->where('so_luong_da_dung', null)
                ->orWhere('so_luong_da_dung', 0)
                ->update(['trang_thai' => TrangThaiNguyenLieuTinh::DELETED()]);

            if ($nguyen_lieu_tinh->so_luong_da_dung) {
                return redirect()->back()->with('error', 'Không thể xóa nguyên liệu tinh đã dùng!')->withInput();
            }

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

            return redirect()->back()->with('success', 'Đã xoá nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function update($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $nguyen_lieu_tinh = NguyenLieuTinh::find($id);
            if (!$nguyen_lieu_tinh || $nguyen_lieu_tinh->trang_thai == TrangThaiNguyenLieuTinh::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nguyên liệu tinh');
            }

            $nguyen_lieu_tinh = $this->saveData($nguyen_lieu_tinh, $request);
            $nguyen_lieu_tinh->save();

            $success = $this->saveDataChiTiet($nguyen_lieu_tinh, $request);

            if (!$success) {
                DB::rollBack();
                return redirect()->route('admin.nguyen.lieu.tinh.index')->with('error', 'Không có đủ nguyên liệu.');
            }

            DB::commit();
            return redirect()->route('admin.nguyen.lieu.tinh.index')->with('success', 'Chỉnh sửa nguyên liệu tinh thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
