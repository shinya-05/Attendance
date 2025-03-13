<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class UserLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        return redirect()->route('attendance.index'); // 一般ユーザーログイン後のリダイレクト
    }
}

