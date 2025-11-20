<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiPhuKien;
use Illuminate\Http\Request;

class AdminLoaiPhuKienController extends Controller
{
    public function index(Request $request)
    {
        $datas = LoaiPhuKien::where('deleted_at', null)->get();
        return view('admin.pages.loai_phu_kien.index', compact('datas'));
    }

    public function detail($id, Request $request)
    {
        $loai_phu_kien = LoaiPhuKien::find($id);
        if (!$loai_phu_kien || $loai_phu_kien->deleted_at) {
            return redirect(route('admin.loai.phu.kien.index'))->with('error', 'Không tìm thấy loại phụ kiện');
        }
        return view('admin.pages.loai_phu_kien.detail', compact('loai_phu_kien'));
    }

    public function update($id, Request $request)
    {
        try {
            $loai_phu_kien = LoaiPhuKien::find($id);
            if (!$loai_phu_kien || $loai_phu_kien->deleted_at) {
                return redirect(route('admin.loai.phu.kien.index'))->with('error', 'Không tìm thấy loại phụ kiện');
            }

            $loai_phu_kien = $this->saveData($loai_phu_kien, $request);
            $loai_phu_kien->save();

            return redirect(route('admin.loai.phu.kien.index'))->with('success', 'Chỉnh sửa loại phụ kiện thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try {
            $loai_phu_kien = new LoaiPhuKien();

            $loai_phu_kien = $this->saveData($loai_phu_kien, $request);
            $loai_phu_kien->save();

            return redirect(route('admin.loai.phu.kien.index'))->with('success', 'Thêm mới loại phụ kiện thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }

    private function saveData(LoaiPhuKien $loai_phu_kien, Request $request)
    {
        $ten_phu_kien = $request->input('ten_phu_kien');
        $ma_phu_kien = $request->input('ma_phu_kien');
        $don_vi_tinh = $request->input('don_vi_tinh');
        $mo_ta_phu_kien = $request->input('mo_ta_phu_kien');

        $loai_phu_kien->ten_phu_kien = $ten_phu_kien;
        $loai_phu_kien->ma_phu_kien = $ma_phu_kien;
        $loai_phu_kien->don_vi_tinh = $don_vi_tinh;
        $loai_phu_kien->mo_ta_phu_kien = $mo_ta_phu_kien;

        return $loai_phu_kien;
    }

    public function delete($id)
    {
        try {
            $loai_phu_kien = LoaiPhuKien::find($id);
            if (!$loai_phu_kien || $loai_phu_kien->deleted_at) {
                return redirect(route('admin.loai.phu.kien.index'))->with('error', 'Không tìm thấy loại phụ kiện');
            }

            $loai_phu_kien->delete();
            return redirect(route('admin.loai.phu.kien.index'))->with('success', 'Xóa loại phụ kiện thành công');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }
}
