<?php

namespace App\Posts;

use App\Post;
use Carbon\Carbon;
use InvalidArgumentException;
use App\Contracts\ChannelContract;
use App\Contracts\Posts\PostsFactoryContract;

class PostsFactory implements PostsFactoryContract
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
     * @throws \InvalidArgumentException
     *
     * @return \App\Contracts\PostContract
     */
    public function make(ChannelContract $channel, string $title, string $message, Carbon $scheduledAt = null, array $attachments = [])
    {
        $bot = $channel->getBot();

        if (is_null($bot)) {
            throw new InvalidArgumentException('Bot must be attached to given channel');
        }

        $post = new Post([
            'title' => $title,
            'message' => $message,
            'scheduled_at' => $scheduledAt,
        ]);

        $autosave = false;

        $post->setAttachments($attachments, $autosave)
            ->shouldBePublishedWith($bot, $channel, $autosave);

        return $post;
    }
}
