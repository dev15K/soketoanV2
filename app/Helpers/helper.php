<?php

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

if (!function_exists('returnMessage')) {
    /**
     * @param int $type
     * @param mixed $data
     * @param string $message
     * @return array
     */
    function returnMessage(int $type, mixed $data, string $message): array
    {
        if ($type === 1) {
            $data = [
                'type' => 'success',
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ];
        } else {
            $data = [
                'type' => 'error',
                'status' => 'error',
                'message' => $message,
                'data' => $data,
            ];
        }

        return $data;
    }
}

if (!function_exists('setting')) {
    function setting()
    {
        if (Schema::hasTable('settings')) {
            return Setting::first();
        }

        return null;
    }
}

if (!function_exists('getRoleUser')) {
    function getRoleUser()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $role_user = RoleUser::where('user_id', $user->id)->first();
            if ($role_user) {
                $role = Role::where('id', $role_user->role_id)->first();
                return $role->name;
            }
        }

        return null;
    }
}
