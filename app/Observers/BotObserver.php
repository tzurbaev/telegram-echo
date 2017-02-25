<?php

namespace App\Observers;

use App\Contracts\BotContract;
use App\Events\Bots\BotCreated;

class BotObserver
{
    public function creating(BotContract $bot)
    {
        $bot->external_id = 0;

        return $bot;
    }

    public function created(BotContract $bot)
    {
        event(new BotCreated($bot));
    }
}
