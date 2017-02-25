<?php

namespace App\Notifications\Service;

use Exception;
use Telegram\Bot\Api;

class TelegramNotificationsSender
{
    /**
     * @var \Telegram\Bot\Api
     */
    protected $telegram;

    /**
     * @var string
     */
    protected $chatId;

    /**
     * @param \Telegram\Bot\Api $telegram
     * @param string $chatId
     */
    public function __construct(Api $telegram, string $chatId)
    {
        $this->telegram = $telegram;
        $this->chatId = $chatId;
    }

    public function notify(string $message)
    {
        try {
            $this->telegram->sendMessage([
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'markdown',
            ]);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
