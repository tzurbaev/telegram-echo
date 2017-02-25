<?php

namespace App\Listeners\Auth;

use App\Jobs\SendTelegramNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegisteredNotification
{
    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $message = trans('notifications.users.registered', [
            'name' => $event->user->name,
            'email' => $event->user->email,
        ]);

        dispatch(new SendTelegramNotification('users.registered.'.$event->user->id, $message));
    }
}
