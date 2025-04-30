<?php

namespace App\Http\Controllers\admin;

use App\Enums\RoleName;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function list()
    {
        $users = User::where('users.status', '!=', UserStatus::DELETED())
            ->join('role_users', 'users.id', '=', 'role_users.user_id')
            ->join('roles', 'role_users.role_id', '=', 'roles.id')
            ->where('roles.name', '!=', RoleName::ADMIN())
            ->orderByDesc('users.id')
            ->select('users.*', 'roles.name as role_name')
            ->get();
        return view('admin.pages.users.list', compact('users'));
    }

    public function detail($id)
    {
        $user = User::where('status', '!=', UserStatus::DELETED())
            ->where('id', $id)
            ->first();
        if (!$user) {
            return redirect()->route('error.not.found');
        }
        return view('admin.pages.users.detail', compact('user'));
    }

    public function create()
    {
        return view('admin.pages.users.create');
    }

    public function store(Request $request)
    {
        try {
            $user = new User();

            $email = $request->input('email');
            $phone = $request->input('phone');
            $password = $request->input('password');
            $password_confirm = $request->input('password_confirm');

            $is_valid = User::checkEmail($email);
            if (!$is_valid) {
                toast('Email has been used!', 'error', 'top-right');
                return redirect()->back();
            }

            $is_valid = User::checkPhone($phone);
            if (!$is_valid) {
                toast('Phone has been used!', 'error', 'top-right');
                return redirect()->back();
            }

            if ($password != $password_confirm) {
                toast('Passwords do not match!', 'error', 'top-right');
                return redirect()->back();
            }

            if (strlen($password) < 5) {
                toast('Password must be at least 5 characters!', 'error', 'top-right');
                return redirect()->back();
            }

            $user = $this->saveUser($request, $user);
            $user->save();

            toast('User created successfully!', 'success', 'top-right');
            return redirect()->route('admin.users.detail', ['id' => $user->id]);
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function update($id, Request $request)
    {
        try {
            $user = User::where('status', '!=', UserStatus::DELETED())
                ->where('id', $id)
                ->first();
            if (!$user) {
                return redirect()->route('error.not.found');
            }

            $email = $request->input('email');
            $phone = $request->input('phone');
            $password = $request->input('password');
            $password_confirm = $request->input('password_confirm');

            if ($user->email != $email) {
                $is_valid = User::checkEmail($email);
                if (!$is_valid) {
                    toast('Email has been used!', 'error', 'top-right');
                    return redirect()->back();
                }
            }

            if ($user->phone != $phone) {
                $is_valid = User::checkPhone($phone);
                if (!$is_valid) {
                    toast('Phone has been used!', 'error', 'top-right');
                    return redirect()->back();
                }
            }

            if ($password || $password_confirm) {
                if ($password != $password_confirm) {
                    toast('Passwords do not match!', 'error', 'top-right');
                    return redirect()->back();
                }

                if (strlen($password) < 5) {
                    toast('Password must be at least 5 characters!', 'error', 'top-right');
                    return redirect()->back();
                }
            }

            $user = $this->saveUser($request, $user);
            $user->save();

            toast('User updated successfully!', 'success', 'top-right');
            return redirect()->route('admin.users.list');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    public function delete($id)
    {
        try {
            $user = User::where('status', '!=', UserStatus::DELETED())
                ->where('id', $id)
                ->first();
            if (!$user) {
                return redirect()->route('error.not.found');
            }

            $user->status = UserStatus::DELETED();
            $user->save();

            toast('User deleted successfully!', 'success', 'top-right');
            return redirect()->route('admin.users.list');
        } catch (\Exception $e) {
            toast($e->getMessage(), 'error', 'top-right');
            return redirect()->back();
        }
    }

    private function saveUser(Request $request, User $user)
    {
        $full_name = $request->input('full_name');
        $username = $request->input('username');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $password = $request->input('password');
        $address = $request->input('address');
        $about = $request->input('about');
        $status = $request->input('status');

        $avatar_path = $user->avatar ?? '';

        if ($request->hasFile('avatar')) {
            $item = $request->file('avatar');
            $itemPath = $item->store('users', 'public');
            $avatar = asset('storage/' . $itemPath);
            $avatar_path = $avatar;
        }

        $user->full_name = $full_name;
        $user->username = $username;
        $user->phone = $phone;
        $user->email = $email;

        if ($request->input('password')) {
            $passwordHash = Hash::make($password);
            $user->password = $passwordHash;
        }
        $user->avatar = $avatar_path;
        $user->address = $address;
        $user->about = $about;
        $user->status = $status;

        return $user;
    }
}
