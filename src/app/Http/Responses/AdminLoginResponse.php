<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class AdminLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return redirect()->route('admin.attendance'); // 管理者ログイン後のリダイレクト
    }
}

