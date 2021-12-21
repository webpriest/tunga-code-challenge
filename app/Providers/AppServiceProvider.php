<?php

namespace App\Providers;

use Illuminate\Http\Request;
use App\Repositories\UploadRepository;
use Illuminate\Support\Facades\Schema;
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
        // Register UploadRepository Service
        $this->app->bind(UploadRepository::class, function(){
           return new UploadRepository();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
