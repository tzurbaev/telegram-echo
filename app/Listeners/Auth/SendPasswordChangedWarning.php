<?php

namespace App\Listeners\Auth;

use App\Events\Auth\PasswordChanged;
use App\Notifications\PasswordChangedNotification;

class SendPasswordChangedWarning
{
    /**
     * Handle the event.
     *
     * @param \App\Events\Auth\PasswordChanged $event
     */
    public function handle(PasswordChanged $event)
    {
        $event->user->notify(new PasswordChangedNotification());
    }
}
