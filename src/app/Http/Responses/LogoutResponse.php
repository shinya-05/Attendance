<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Illuminate\Http\Request;

class LogoutResponse implements LogoutResponseContract
{
    public function toResponse($request)
    {
        return redirect('/login'); // ログアウト後にログインページへリダイレクト
    }
}
