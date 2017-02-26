<?php

namespace App\Http\Requests;

use App\Channel;
use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $channel = Channel::find($this->input('channel_id'));

        return !is_null($channel) && $channel->hasMember($this->user());
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
}
