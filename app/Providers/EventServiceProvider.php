<?php

namespace App\Providers;

use App\Bot;
use App\User;
use App\Channel;
use App\Observers\BotObserver;
use App\Observers\UserObserver;
use App\Observers\ChannelObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Posts\ScheduledPostWasNotPublished' => [
            'App\Listeners\Posts\NotifyPostOwnerAboutPublicationFail',
        ],

        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\Auth\SendWelcomeEmail',
            'App\Listeners\Auth\SendRegisteredNotification',
        ],

        'App\Events\Auth\PasswordChanged' => [
            'App\Listeners\Auth\SendPasswordChangedWarning',
        ],

        'App\Events\Bots\BotCreated' => [
            'App\Listeners\Bots\ResolveBotInfo',
        ],
    ];

    public function boot()
    {
        parent::boot();

        User::observe(UserObserver::class);
        Channel::observe(ChannelObserver::class);
        Bot::observe(BotObserver::class);
    }
}
