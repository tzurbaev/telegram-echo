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
     *
     * @return \App\Contracts\ChannelContract
     */
    public function make(UserContract $user, string $name);
}
