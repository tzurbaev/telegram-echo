<?php

namespace App\Providers;

use App\Posts\Publisher;
use App\Channels\ChannelsFactory;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Posts\PublisherContract;
use App\Contracts\Channels\ChannelsFactoryContract;
use App\Notifications\Service\TelegramNotificationsSender;

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
        $this->app->singleton(TelegramNotificationsSender::class, function ($app) {
            $token = $app['config']->get('telegram.token');
            $chatId = $app['config']->get('telegram.chat');

            return new TelegramNotificationsSender($token, $chatId);
        });

        $this->bindContractsToImplementations();
    }

    protected function bindContractsToImplementations()
    {
        $map = [
            ChannelsFactoryContract::class => ChannelsFactory::class,
            PublisherContract::class => Publisher::class,
        ];

        collect($map)->each(function ($concrete, $abstraction) {
            $this->app->bind($abstraction, $concrete);
        });
    }
}
