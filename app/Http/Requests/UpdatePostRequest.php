<?php

namespace App\Http\Requests;

use App\Channel;
use App\Contracts\PostContract;
use App\Helpers\DateTimeHelper;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * @var \App\Contracts\ChannelContract
     */
    protected $channel;

    /**
     * @var \App\Contracts\PostContract
     */
    protected $currentPost;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->currentPost = $this->route('post');

        if (!($this->currentPost instanceof PostContract)) {
            return false;
        }

        if (!$this->has('channel_id')) {
            return true;
        }

        // Если в текущем запросе был передан ID канала,
        // нам нужно убедиться, что этот пользователь
        // имеет доступ к публикациям нового канала.

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
            'channel_id' => 'integer|exists:channels,id',
            'title' => 'string',
            'message' => 'string',
            'scheduled_at' => 'date_format:Y-m-d H:i',
            'attachments.*.type' => 'string|in:photo,video,audio,location',
            'attachments.*.params' => 'array',
            'remove_attachments' => 'boolean',
        ];
    }

    public function updatePost(array $fields, DateTimeHelper $dates)
    {
        $autosave = false;

        $fields['scheduled_at'] = $dates->extractFromRequest($this, 'scheduled_at', 'Y-m-d H:i:s', $this->user()->timezone);

        $attachments = $this->input('attachments');
        $remove = $this->has('remove_attachments');

        $this->currentPost
            ->updateOrRemoveAttachments($attachments, $remove, $autosave)
            ->update($fields);

        return $this->currentPost;
    }
}
