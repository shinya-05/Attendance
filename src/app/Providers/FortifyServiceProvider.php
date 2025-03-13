<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Requests\LoginRequest;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use App\Http\Responses\LogoutResponse;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\AdminLoginResponse;
use App\Http\Responses\UserLoginResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);


        // カスタムログインレスポンスを登録
        $this->app->singleton(LoginResponseContract::class, function ($app) {
            return request()->is('admin/*') 
                ? $app->make(\App\Http\Responses\AdminLoginResponse::class) 
                : $app->make(\App\Http\Responses\UserLoginResponse::class);
        });


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::registerView(function () {
        return view('auth.register');
        });

        // ユーザー認証
        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();
            return $user && Hash::check($request->password, $user->password) ? $user : null;
        });

        // 管理者認証
        Fortify::authenticateUsing(function (Request $request) {
            $admin = Admin::where('email', $request->email)->first();
            return $admin && Hash::check($request->password, $admin->password) ? $admin : null;
        });

        // ログインページの分岐
        Fortify::loginView(function () {
            return request()->is('admin/*') ? view('auth.admin-login') : view('auth.login');
        });


        RateLimiter::for('login', function (Request $request) {
        $email = (string) $request->email;

        return Limit::perMinute(10)->by($email . $request->ip());
        });

        //デフォルトのログイン機能にあるフォームリクエストを自作のものに代替するため、サービスコンテナにバインド
        app()->bind(FortifyLoginRequest::class, LoginRequest::class);


    }

}
