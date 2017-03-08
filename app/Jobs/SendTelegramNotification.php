<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Service\TelegramNotificationsSender;

class SendTelegramNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    public $hash;

    /**
     * @var string
     */
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param string $message
     */
    public function __construct(string $hash, string $message)
    {
        $this->hash = $hash;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(TelegramNotificationsSender $sender)
    {
        $sender->notify($this->message);
    }
}
