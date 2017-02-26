<?php

namespace App\Channels;

use Illuminate\Support\Str;
use App\Contracts\UserContract;
use App\Contracts\Channels\ChannelsFactoryContract;

class ChannelsFactory implements ChannelsFactoryContract
{
    /**
     * Создает новый канал.
     *
     * @param \App\Contracts\UserContract $user
     * @param string                      $name
     * @param string|int                  $chatId
     *
     * @return \App\Contracts\ChannelContract
     */
    public function make(UserContract $user, string $name, $chatId)
    {
        $channel = $user->channels()->create([
            'slug' => Str::slug($name),
            'name' => $name,
            'chat_id' => $chatId,
        ]);

        $channel->addMember($user);

        return $channel;
    }
}
