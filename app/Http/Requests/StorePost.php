<?php

namespace App\Http\Requests;

use App\Channel;
use App\Helpers\DateTimeHelper;
use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Posts\PostsFactoryContract;
use App\Exceptions\Api\BotWasNotFoundException;

class StorePost extends FormRequest
{
    /**
     * @var \App\Contracts\ChannelContract
     */
    protected $channel;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->channel = Channel::find($this->input('channel_id'));

        return !is_null($this->channel) && $this->channel->hasMember($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'channel_id' => 'required|integer|exists:channels,id',
            'title' => 'string',
            'message' => 'string',
            'scheduled_at' => 'date_format:Y-m-d H:i',
            'attachments.*.type' => 'string|in:photo,video,audio,location',
            'attachments.*.params' => 'array',
        ];
    }

    public function createPost(PostsFactoryContract $posts, DateTimeHelper $dates)
    {
        if (!$this->channel->hasBot()) {
            throw new BotWasNotFoundException();
        }

        $title = $this->input('title');
        $message = $this->input('message');
        $scheduledAt = $dates->extractFromRequest($this, 'scheduled_at', 'Y-m-d H:i', $this->user()->timezone);
        $attachments = $this->input('attachments', []);

        return $posts->make($this->channel, $title, $message, $scheduledAt, $attachments);
    }
}
