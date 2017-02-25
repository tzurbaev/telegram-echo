<?php

namespace App\Contracts\Channels;

use App\Contracts\UserContract;

interface ChannelsFactoryContract
{
    /**
     * Создает новый канал.
     *
     * @param \App\Contracts\UserContract $user
     * @param string                      $name
     * @param string|int $chatId
     *
     * @return \App\Contracts\ChannelContract
     */
    public function make(UserContract $user, string $name, $chatId);
}
