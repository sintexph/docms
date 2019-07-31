<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Upload;
use App\Observers\UploadObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Upload::observe(UploadObserver::class);
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
