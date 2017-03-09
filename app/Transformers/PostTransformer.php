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
            'scheduled_at_formatted' => [
                'date' => !is_null($post->scheduled_at) ? DateTimeHelper::formatLocalized('date.raw', $post->scheduled_at) : null,
                'time' => !is_null($post->scheduled_at) ? DateTimeHelper::formatLocalized('time.single', $post->scheduled_at) : null,
            ],
            'published_at' => $post->wasPublished() ? DateTimeHelper::formatLocalized('date.time', $post->published_at) : null,
            'urls' => [
                'update' => [
                    'url' => route('api.posts.update', ['post' => $post->id]),
                    'method' => 'put',
                ],
                'destroy' => [
                    'url' => route('api.posts.destroy', ['post' => $post->id]),
                    'method' => 'delete',
                ],
            ],
            'created_at' => DateTimeHelper::formatLocalized('date.time', $post->created_at),
            'updated_at' => DateTimeHelper::formatLocalized('date.time', $post->updated_at),
        ];
    }
}
