<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChannel extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();

        $channel = $this->route('channel');

        if (is_null($channel)) {
            return false;
        }

        // Если в текущем запросе связанный бот не обновляется,
        // можно пропустить процесс валидации владельца бота.

        if (!$this->has('bot_id')) {
            return true;
        }

        // Иначе проверяем, что пользователь передал
        // ID бота, который принадлежит именно ему.

        $bot = $user->bots()->find($this->input('bot_id'));

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
            'name' => 'string|max:255',
            'chat_id' => [
                'string',
                'max:255',
                Rule::unique('channels')->ignore($this->route('channel')),
            ],
            'bot_id' => 'integer|exists:bots,id',
        ];
    }
}
