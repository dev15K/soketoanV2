<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.pages.profile');
    }

    public function changeInfo(Request $request)
    {
        try {

            return redirect()->route('admin.profile.index')->with('success', 'Thay đổi thông tin cá nhân thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        try {

            return redirect()->route('admin.profile.index')->with('success', 'Đổi mật khẩu thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
