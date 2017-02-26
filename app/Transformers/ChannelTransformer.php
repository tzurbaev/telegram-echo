<?php

namespace App\Transformers;

use App\Channel;
use Facades\App\Helpers\DateTimeHelper;
use League\Fractal\TransformerAbstract;

class ChannelTransformer extends TransformerAbstract
{
    public function transform(Channel $channel)
    {
        return [
            'id' => intval($channel->id),
            'name' => $channel->name,
            'slug' => $channel->slug,
            'bot_id' => intval($channel->bot_id),
            'chat_id' => $channel->chat_id,
            'external_id' => $channel->external_id,
            'has_bot' => !is_null($channel->bot_id) ? 1 : 0,
            'urls' => [
                'update' => [
                    'url' => route('api.channels.update', ['channel' => $channel->id]),
                    'method' => 'put',
                ],
                'destroy' => [
                    'url' => route('api.channels.destroy', ['channel' => $channel->id]),
                    'method' => 'delete',
                ],
            ],
            'created_at' => DateTimeHelper::formatLocalized('date.time', $channel->created_at),
            'updated_at' => DateTimeHelper::formatLocalized('date.time', $channel->updated_at),
        ];
    }
}
