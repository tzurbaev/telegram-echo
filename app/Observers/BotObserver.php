<?php

namespace App\Observers;

use App\Bot;
use App\Events\Bots\BotCreated;

class BotObserver
{
    public function creating(Bot $bot)
    {
        $bot->external_id = 0;

        return $bot;
    }

    public function created(Bot $bot)
    {
        event(new BotCreated($bot));
    }
}
