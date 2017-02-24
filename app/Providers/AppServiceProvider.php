<?php

namespace App\Providers;

use App\Channels\ChannelsFactory;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Channels\ChannelsFactoryContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindContractsToImplementations();
    }

    protected function bindContractsToImplementations()
    {
        $map = [
            ChannelsFactoryContract::class => ChannelsFactory::class,
        ];

        collect($map)->each(function ($concrete, $abstraction) {
            $this->app->bind($abstraction, $concrete);
        });
    }
}
