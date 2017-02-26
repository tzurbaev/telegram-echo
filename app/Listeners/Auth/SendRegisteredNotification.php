<?php

namespace App\Listeners\Auth;

use App\Jobs\SendTelegramNotification;
use Illuminate\Auth\Events\Registered;

class SendRegisteredNotification
{
    /**
     * Handle the event.
     *
     * @param \Illuminate\Auth\Events\Registered $event
     */
    public function handle(Registered $event)
    {
        $message = trans('notifications.users.registered', [
            'name' => $event->user->name(),
            'email' => $event->user->email(),
        ]);

        dispatch(new SendTelegramNotification('users.registered.'.$event->user->id(), $message));
    }
}
