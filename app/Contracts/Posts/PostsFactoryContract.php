<?php

namespace App\Contracts\Posts;

use Carbon\Carbon;
use App\Contracts\ChannelContract;

interface PostsFactoryContract
{
    /**
     * Создает экземлпяр новой записи без сохранения в базу.
     *
     * @param \App\Contracts\ChannelContract $channel
     * @param string                         $title
     * @param string                         $message
     * @param \Carbon\Carbon                 $scheduledAt = null
     * @param attachments = null
     *
     * @return \App\Contracts\PostContract
     */
    public function make(ChannelContract $channel, string $title, string $message, Carbon $scheduledAt = null, array $attachments = []);
}
