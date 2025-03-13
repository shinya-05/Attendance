<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController as FortifyAuthenticatedSessionController;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Laravel\Fortify\Http\Requests\LoginRequest; // FortifyのLoginRequestを使用
use Laravel\Fortify\Fortify;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends FortifyAuthenticatedSessionController
{
    public function store(LoginRequest $request): LoginResponseContract
    {
        $guard = $request->is('admin/*') ? 'admin' : 'web';

        $request->authenticate($guard);

        $request->session()->regenerate();

        return app(LoginResponseContract::class); // ✅ グローバル名前空間を明示的に指定

    }


    public function destroy(Request $request): LogoutResponseContract
    {
        $guard = Auth::guard('admin')->check() ? 'admin' : 'web';
        Auth::guard($guard)->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return app(LogoutResponseContract::class);
    }
}