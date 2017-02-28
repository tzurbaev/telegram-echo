<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $bot = $this->user()->bots()->find($this->input('bot_id'));

        return !is_null($bot);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'chat_id' => 'required|string|max:255,unique:channels',
            'bot_id' => 'required|integer|exists:bots,id',
        ];
    }
}
