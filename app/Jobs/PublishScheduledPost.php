<?php

namespace App\Jobs;

use RuntimeException;
use Telegram\Bot\Api;
use Illuminate\Bus\Queueable;
use App\Contracts\PostContract;
use App\Transports\TelegramTransport;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Contracts\Posts\PublisherContract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Posts\ScheduledPostWasNotPublished;

class PublishScheduledPost implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Contracts\PostContract
     */
    public $post;

    /**
     * Create a new job instance.
     *
     * @param \App\Contracts\PostContract $post
     */
    public function __construct(PostContract $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @param \App\Contracts\Posts\PublisherContract $publisher
     */
    public function handle(PublisherContract $publisher)
    {
        $telegram = new Api($this->post->getBot()->apiToken());
        $transport = new TelegramTransport($telegram);

        $wasPublished = false;

        try {
            $publisher->withTransport($transport)->publish($this->post);
        } catch (RuntimeException $e) {
            $wasPublished = false;
        }

        if ($wasPublished === false) {
            event(new ScheduledPostWasNotPublished($this->post));
        }
    }
}
