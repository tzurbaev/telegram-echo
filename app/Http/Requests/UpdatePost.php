<?php

namespace App\Http\Requests;

use App\Channel;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $post = $user->posts()->find($this->route('post'));

        if (is_null($post)) {
            return false;
        }

        if (!$this->has('channel_id')) {
            return true;
        }

        // Если в текущем запросе был передан ID канала,
        // нам нужно убедиться, что этот пользователь
        // имеет доступ к публикациям нового канала.

        $channel = Channel::find($this->input('channel_id'));

        return !is_null($channel) && $channel->hasMember($user);
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
            'message' => 'string',
            'scheduled_at' => 'date_format:Y-m-d H:i',
            'attachments.*.type' => 'string|in:photo,video,audio,location',
            'attachments.*.params' => 'array',
            'remove_attachments' => 'boolean',
        ];
    }
}
