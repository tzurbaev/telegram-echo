<?php

namespace App\Posts;

use App\Contracts\Posts\MessageProcessorContract;

class MessageProcessor implements MessageProcessorContract
{
    /**
     * Обрабатывает сообщение перед публикацией.
     *
     * @param string $message
     *
     * @return string
     */
    public function processBeforePublishing(string $message): string
    {
        // Telegram поддерживает нестандартный Markdown-синтаксис.
        // Bold в Telegram реализуется с помощью одинарных "*".

        return str_replace('**', '*', $message);
    }
}
