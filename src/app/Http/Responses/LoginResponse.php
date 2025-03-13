<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();

        if ($user->is_admin) {
            return redirect()->route('admin.dashboard'); // 管理者の場合
        }

        return redirect()->route('attendance.index'); // 一般ユーザーの場合
    }
}
