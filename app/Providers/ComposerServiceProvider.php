<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\ApplicationStateComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     */
    public function boot()
    {
        View::composer('layouts.app', ApplicationStateComposer::class);
    }

    /**
     * Register the application services.
     *
     */
    public function register()
    {
        //
    }
}
