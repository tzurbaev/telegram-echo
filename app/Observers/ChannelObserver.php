<?php

namespace App\Observers;

use App\Channel;
use Illuminate\Support\Str;

class ChannelObserver
{
    public function updating(Channel $channel)
    {
        if ($channel->getOriginal('name') !== $channel->name) {
            $channel->slug = Str::slug($channel->name);
        }

        return $channel;
    }
}
