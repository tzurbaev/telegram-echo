<?php

namespace App\Contracts;

interface BotContract
{
    /**
     * Запускает отправку сообщения в канал.
     *
     * @param string $message = null
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function broadcast(string $message = null);

    /**
     * API-токен бота.
     *
     * @return string
     */
    public function apiToken(): string;
}
