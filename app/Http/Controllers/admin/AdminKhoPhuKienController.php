<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiSanPham;
use App\Http\Controllers\Controller;
use App\Models\KhoPhuKien;
use App\Models\LichSuKhoPhuKien;
use App\Models\LoaiPhuKien;
use App\Models\SanPham;
use Illuminate\Http\Request;

class AdminKhoPhuKienController extends Controller
{
    public function index()
    {
        $datas = KhoPhuKien::where('deleted_at', null)->orderByDesc('id')->get();

        $products = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->orderByDesc('id')
            ->get();

        $loaiPhuKiens = LoaiPhuKien::where('deleted_at', null)->orderByDesc('id')->get();
        return view('admin.pages.phu_kien_san_pham.index', compact('datas', 'products', 'loaiPhuKiens'));
    }

    public function detail($id)
    {
        $kho = KhoPhuKien::find($id);
        if (!$kho || $kho->deleted_at != null) {
            return redirect()->back()->with('error', 'Không tìm thấy kho phụ kiện');
        }

        $products = SanPham::where('trang_thai', '!=', TrangThaiSanPham::DELETED())
            ->orderByDesc('id')
            ->get();

        $loaiPhuKiens = LoaiPhuKien::where('deleted_at', null)->orderByDesc('id')->get();

        return view('admin.pages.phu_kien_san_pham.detail', compact('kho', 'products', 'loaiPhuKiens'));
    }

    public function store(Request $request)
    {
        try {
            $kho = new KhoPhuKien();

            $isExist = $this->checkKhoExist($request);
            if ($isExist) {
                return redirect()->back()->with('error', 'Kho phụ kiện đã được tạo rồi!')->withInput();
            }

            $kho = $this->save($kho, $request);
            $kho->save();

            $this->updateOrCreateKho($kho, $request, 1);

            return redirect(route('admin.kho.phu.kien.index'))->with('success', 'Thêm kho phụ kiện thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function checkKhoExist(Request $request)
    {
        $created = false;

        $san_pham_id = $request->input('san_pham_id');
        $loai_phu_kien_id = $request->input('loai_phu_kien_id');

        $kho = KhoPhuKien::where('san_pham_id', $san_pham_id)
            ->where('loai_phu_kien_id', $loai_phu_kien_id)
            ->where('deleted_at', null)
            ->first();

        if ($kho) {
            $created = true;
        }

        return $created;
    }

    public function update($id, Request $request)
    {
        try {
            $kho = KhoPhuKien::find($id);
            if (!$kho || $kho->deleted_at != null) {
                return redirect()->back()->with('error', 'Không tìm thấy kho phụ kiện');
            }

            $kho = $this->save($kho, $request);
            $kho->save();

            return redirect(route('admin.kho.phu.kien.index'))->with('success', 'Chỉnh sửa kho phụ kiện thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    private function save(KhoPhuKien $khoPhuKien, Request $request)
    {
        $san_pham_id = $request->input('san_pham_id');
        $loai_phu_kien_id = $request->input('loai_phu_kien_id');
        $so_luong = $request->input('so_luong');

        $khoPhuKien->san_pham_id = $san_pham_id;
        $khoPhuKien->loai_phu_kien_id = $loai_phu_kien_id;
        $khoPhuKien->so_luong = $so_luong;

        return $khoPhuKien;
    }

    private function updateOrCreateKho(KhoPhuKien $khoPhuKien, Request $request, int $type)
    {
        if ($type == 1) {
            $lishSuKho = new LichSuKhoPhuKien();

            $lishSuKho->kho_phu_kien_id = $khoPhuKien->id;
            $lishSuKho->loai_phu_kien_id = $khoPhuKien->loai_phu_kien_id;
            $lishSuKho->so_luong = $khoPhuKien->so_luong;
            $lishSuKho->save();
            return $lishSuKho;
        } else {
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $kho = KhoPhuKien::find($id);
            if (!$kho || $kho->deleted_at != null) {
                return redirect()->back()->with('error', 'Không tìm thấy kho phụ kiện');
            }
            $kho->delete();

            return redirect()->back()->with('success', 'Đã xoá kho phụ kiện thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}
