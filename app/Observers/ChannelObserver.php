<?php

namespace App\Observers;

use App\Channel;
use Illuminate\Support\Str;
use App\Validator\CustomRules;

class ChannelObserver
{
    /**
     * @var \App\Validator\CustomRules
     */
    protected $customRules;

    /**
     * @param \App\Validator\CustomRules $customRules
     */
    public function __construct(CustomRules $customRules)
    {
        $this->customRules = $customRules;
    }

    /**
     * @param \App\Channel $channel
     *
     * @return \App\Channel
     */
    public function creating(Channel $channel)
    {
        $this->sanitizeChatId($channel);

        return $channel;
    }

    /**
     * @param \App\Channel $channel
     *
     * @return \App\Channel
     */
    public function updating(Channel $channel)
    {
        if ($channel->getOriginal('name') !== $channel->name) {
            $channel->slug = Str::slug($channel->name);
        }

        $this->sanitizeChatId($channel);

        return $channel;
    }

    /**
     * @param \App\Channel $channel
     */
    protected function sanitizeChatId(Channel $channel)
    {
        $channel->chat_id = '@'.$this->customRules->sanitizeTelegramUsername($channel->chat_id);
    }
}
