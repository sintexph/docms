<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Upload;
use App\DocumentVersion;
use App\Observers\UploadObserver;
use App\Observers\DocumentVersionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Upload::observe(UploadObserver::class);
        DocumentVersion::observe(DocumentVersionObserver::class);
    }
}
