<?php

namespace App\Events\Auth;

use App\Contracts\UserContract;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PasswordChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Contracts\UserContract
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \App\Contracts\UserContract
     */
    public function __construct(UserContract $user)
    {
        $this->user = $user;
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
