<?php

namespace App\Contracts\Transports;

use App\Contracts\Messages\FluentMessageContract;

interface TransportContract
{
    /**
     * Выполняет отправку сообщения.
     *
     * @param \App\Contracts\Messages\FluentMessageContract $message
     *
     * @throws \InvalidArgumentException
     *
     * @return bool
     */
    public function send(FluentMessageContract $message);
}
