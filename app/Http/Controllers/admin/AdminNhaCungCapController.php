<?php

namespace App\Http\Controllers\admin;

use App\Enums\TrangThaiNhaCungCap;
use App\Http\Controllers\Controller;
use App\Models\NhaCungCaps;
use Illuminate\Http\Request;

class AdminNhaCungCapController extends Controller
{
    public function index()
    {
        $datas = NhaCungCaps::where('trang_thai', '!=', TrangThaiNhaCungCap::DELETED())
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.pages.nha_cung_cap.index', compact('datas'));
    }

    public function detail($id)
    {
        $ncc = NhaCungCaps::find($id);
        if (!$ncc || $ncc->trang_thai == TrangThaiNhaCungCap::DELETED()) {
            return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
        }
        return view('admin.pages.nha_cung_cap.detail', compact('ncc'));
    }

    public function store(Request $request)
    {
        try {
            $ten = $request->input('ten');
            $dia_chi = $request->input('dia_chi');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $tinh_thanh = $request->input('tinh_thanh');
            $trang_thai = $request->input('trang_thai');

            $ncc = new NhaCungCaps();

            $ncc->ten = $ten;
            $ncc->dia_chi = $dia_chi ?? '';
            $ncc->so_dien_thoai = $so_dien_thoai ?? '';
            $ncc->tinh_thanh = $tinh_thanh ?? '';
            $ncc->trang_thai = $trang_thai;
            $ncc->save();

            return redirect()->back()->with('success', 'Thêm mới nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update($id, Request $request)
    {
        try {
            $ten = $request->input('ten');
            $dia_chi = $request->input('dia_chi');
            $so_dien_thoai = $request->input('so_dien_thoai');
            $tinh_thanh = $request->input('tinh_thanh');
            $trang_thai = $request->input('trang_thai');

            $ncc = NhaCungCaps::find($id);
            if (!$ncc || $ncc->trang_thai == TrangThaiNhaCungCap::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
            }

            $ncc->ten = $ten;
            $ncc->dia_chi = $dia_chi ?? '';
            $ncc->so_dien_thoai = $so_dien_thoai ?? '';
            $ncc->tinh_thanh = $tinh_thanh ?? '';
            $ncc->trang_thai = $trang_thai;
            $ncc->save();

            return redirect()->route('admin.nha.cung.cap.index')->with('success', 'Chỉnh sửa nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $ncc = NhaCungCaps::find($id);
            if (!$ncc || $ncc->trang_thai == TrangThaiNhaCungCap::DELETED()) {
                return redirect()->back()->with('error', 'Không tìm thấy nhà cung cấp');
            }

            $ncc->trang_thai = TrangThaiNhaCungCap::DELETED();
            $ncc->save();

            return redirect()->back()->with('success', 'Đã xoá nhà cung cấp thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
