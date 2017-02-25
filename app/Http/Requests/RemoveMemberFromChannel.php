<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RemoveMemberFromChannel extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        $removingId = intval($this->input('user_id'));

        $channel = $user->channels()->find($this->route('channel'));

        // Пользователей может удалять только создатель канала.
        // Кроме того, пользователь может сам уйти из канала.
        // В этом случае этот запрос так же будет корректен.

        return !is_null($channel) && ($channel->isCreator($this->user()) || $user->id === $removingId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
