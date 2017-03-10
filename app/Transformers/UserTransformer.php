<?php

namespace App\Transformers;

use App\User;
use Facades\App\Helpers\DateTimeHelper;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => intval($user->id),
            'name' => $user->name,
            'email' => $user->email,
            'timezone' => $user->timezone,
            'timezone_name' => trans('timezones.'.$user->timezone.'.name'),
            'created_at' => DateTimeHelper::formatLocalized('date.time', $user->created_at),
            'updated_at' => DateTimeHelper::formatLocalized('date.time', $user->updated_at),
        ];
    }
}
