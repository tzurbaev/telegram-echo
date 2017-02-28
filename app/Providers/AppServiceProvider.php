<?php

namespace App\Providers;

use Telegram\Bot\Api;
use App\Posts\Publisher;
use App\Posts\PostsFactory;
use App\Posts\MessageProcessor;
use App\Channels\ChannelsFactory;
use Illuminate\Support\ServiceProvider;
use App\Contracts\Posts\PublisherContract;
use App\Contracts\Posts\PostsFactoryContract;
use App\Contracts\Posts\MessageProcessorContract;
use App\Contracts\Channels\ChannelsFactoryContract;
use App\Notifications\Service\TelegramNotificationsSender;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     */
    public function register()
    {
        $this->app->singleton(TelegramNotificationsSender::class, function ($app) {
            $token = $app['config']->get('telegram.token');
            $chatId = $app['config']->get('telegram.chat');

            $telegram = new Api($token);

            return new TelegramNotificationsSender($telegram, $chatId);
        });

        $this->bindContractsToImplementations();
    }

    protected function bindContractsToImplementations()
    {
        $map = [
            ChannelsFactoryContract::class => ChannelsFactory::class,
            PostsFactoryContract::class => PostsFactory::class,
            PublisherContract::class => Publisher::class,
            MessageProcessorContract::class => MessageProcessor::class,
        ];

        collect($map)->each(function ($concrete, $abstraction) {
            $this->app->bind($abstraction, $concrete);
        });
    }
}
