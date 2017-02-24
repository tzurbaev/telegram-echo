<?php

namespace App\Posts;

use Carbon\Carbon;
use RuntimeException;
use Illuminate\Support\Str;
use App\Contracts\PostContract;
use App\Contracts\Posts\PublisherContract;
use App\Contracts\Transports\TransportContract;

class Publisher implements PublisherContract
{
    /**
     * @var \App\Contracts\Transports\TransportContract
     */
    protected $transport;

    /**
     * @param \App\Contracts\Transports\TransportContract $transport
     */
    public function __construct(TransportContract $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Публикует запись.
     *
     * @param \App\Contracts\PostContract $post
     *
     * @return bool
     */
    public function publish(PostContract $post)
    {
        $message = $this->messageFromPost($post);

        try {
            $this->transport->send($message);
        } catch (RuntimeException $e) {
            return false;
        }

        $post->setPublished(Carbon::now());

        return true;
    }

    /**
     * Создаем FluentMessage из записи.
     *
     * @param \App\Contracts\PostContract $post
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    protected function messageFromPost(PostContract $post)
    {
        $message = $post->getBot()
            ->broadcast($post->message ?? '')
            ->to($post->getChannel());

        if (!$post->hasAttachments()) {
            return $message;
        }

        $post->attachmentsCollection()
            ->each(function ($attachment) use ($message) {
                call_user_func_array([$message, 'with'.Str::studly($attachment['type'])], $attachment['params']);
            });

        return $message;
    }
}
