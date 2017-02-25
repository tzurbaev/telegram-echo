<?php

namespace App\Events\Bots;

use App\Contracts\BotContract;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class BotCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Contracts\BotContract
     */
    public $bot;

    /**
     * Create a new event instance.
     *
     * @param \App\Contracts\BotContract
     */
    public function __construct(BotContract $bot)
    {
        $this->bot = $bot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
