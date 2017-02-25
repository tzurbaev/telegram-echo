<?php

namespace App\Observers;

use App\User;
use App\Events\Auth\PasswordChanged;

class UserObserver
{
    /**
     * Событие срабатывает после изменения данных пользователя.
     * Здесь выполняется отправка уведомления о смене пароля (если он был изменен).
     *
     * @param \App\User
     */
    public function updated(User $user)
    {
        if ($user->getOriginal('password') !== $user->password) {
            event(new PasswordChanged($user));
        }
    }
}
