<?php

namespace App\Listeners\Bots;

use Telegram\Bot\Api;
use App\Events\Bots\BotCreated;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Exceptions\Api\BotWasNotCreatedException;
use Telegram\Bot\Exceptions\TelegramResponseException;

class ResolveBotInfo
{
    /**
     * Handle the event.
     *
     * @param \App\Events\Bots\BotCreated $event
     */
    public function handle(BotCreated $event)
    {
        $bot = new Api($event->bot->apiToken());

        $info = null;

        try {
            $info = $bot->getMe();
        } catch (TelegramResponseException $e) {
            Log::error('[ResolveBotInfo@handle] Unable to resolve bot info', [
                'bot_id' => $event->bot->id(),
                'exception' => $e,
            ]);

            $event->bot->delete();

            throw new BotWasNotCreatedException('Unable to finish bot creation process due to API error');
        }

        if (!($info instanceof Collection)) {
            throw new BotWasNotCreatedException('Unable to finish bot creation process due to API error');
        }

        $event->bot->update([
            'name' => $info->getFirstName(),
            'username' => '@'.$info->getUsername(),
            'external_id' => $info->getId(),
        ]);
    }
}
