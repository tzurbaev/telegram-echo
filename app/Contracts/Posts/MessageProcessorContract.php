<?php

namespace App\Contracts\Posts;

interface MessageProcessorContract
{
    /**
     * Обрабатывает сообщение перед публикацией.
     *
     * @param string $message
     *
     * @return string
     */
    public function processBeforePublishing(string $message): string;
}
