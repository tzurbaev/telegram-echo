<?php

namespace App\Transformers;

use App\Post;
use Facades\App\Helpers\DateTimeHelper;
use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{
    public function transform(Post $post)
    {
        return [
            'id' => intval($post->id),
            'channel_id' => intval($post->channel_id),
            'channel_name' => $post->channel->name,
            'title' => $post->title ?? '',
            'message' => $post->message ?? '',
            'attachments' => $post->attachmentsCollection()->toArray(),
            'was_published' => $post->wasPublished(),
            'scheduled' => $post->isScheduled(),
            'scheduled_at' => !is_null($post->scheduled_at) ? DateTimeHelper::formatLocalized('date.time', $post->scheduled_at) : null,
            'published_at' => $post->wasPublished() ? DateTimeHelper::formatLocalized('date.time', $post->published_at) : null,
            'created_at' => DateTimeHelper::formatLocalized('date.time', $post->created_at),
            'updated_at' => DateTimeHelper::formatLocalized('date.time', $post->updated_at),
        ];
    }
}
