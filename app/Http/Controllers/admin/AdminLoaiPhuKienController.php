<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LoaiPhuKien;
use Illuminate\Http\Request;

class AdminLoaiPhuKienController extends Controller
{
    public function index(Request $request)
    {
        $loaiPhuKiens = LoaiPhuKien::where('deleted_at', null)->get();
        return view('admin.pages.loai_phu_kien.index', compact('loaiPhuKiens'));
    }

    public function detail($id, Request $request)
    {
        $loai_phu_kien = LoaiPhuKien::find($id);
        if (!$loai_phu_kien || $loai_phu_kien->deleted_at) {
            
        }
        return view('admin.pages.loai_phu_kien.detail');
    }

    public function update($id, Request $request)
    {
        try {

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function store(Request $request)
    {
        try {

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }

    public function delete($id)
    {
        try {

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage())->withInput();
        }
    }
}
