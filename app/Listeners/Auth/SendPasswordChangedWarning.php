<?php

namespace App\Listeners\Auth;

use App\Events\Auth\PasswordChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
