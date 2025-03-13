<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //前者はパッケージにあるデフォルトで呼び出されるクラス
        //後者はデフォルトのクラスに少し変更を加えたクラス
        $this->app->bind(PackageHasInDatabase::class, HasInDatabase::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
