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
        $channel = $this->user()->channels()->find($this->route('channel'));

        return !is_null($channel);
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
        ];
    }
}
