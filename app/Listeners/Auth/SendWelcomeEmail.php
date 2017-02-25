<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Registered;
use App\Notifications\WelcomeNotification;

class SendWelcomeEmail
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     */
    public function handle(Registered $event)
    {
        $event->user->notify(new WelcomeNotification());
    }
}
