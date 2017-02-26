<?php

namespace App\Transformers;

use App\Bot;
use Facades\App\Helpers\DateTimeHelper;
use League\Fractal\TransformerAbstract;

class BotTransformer extends TransformerAbstract
{
    public function transform(Bot $bot)
    {
        return [
            'id' => intval($bot->id),
            'external_id' => $bot->external_id,
            'name' => $bot->name,
            'username' => $bot->username,
            'token' => $bot->apiToken(),
            'urls' => [
                'update' => [
                    'url' => route('api.bots.update', ['bot' => $bot->id]),
                    'method' => 'put',
                ],
                'destroy' => [
                    'url' => route('api.bots.destroy', ['bot' => $bot->id]),
                    'method' => 'delete',
                ],
            ],
            'created_at' => DateTimeHelper::formatLocalized('date.time', $bot->created_at),
            'updated_at' => DateTimeHelper::formatLocalized('date.time', $bot->updated_at),
        ];
    }
}
