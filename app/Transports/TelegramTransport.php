<?php

namespace App\Transports;

use Exception;
use RuntimeException;
use Telegram\Bot\Api;
use Illuminate\Support\Str;
use InvalidArgumentException;
use App\Contracts\BotContract;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Traits\Macroable;
use App\Contracts\Transports\TransportContract;
use App\Contracts\Messages\FluentMessageContract;

class TelegramTransport implements TransportContract
{
    use Macroable;

    /**
     * Telegram API
     *
     * @var \Telegram\Bot\Api
     */
    protected $telegram;

    /**
     * @param \Telegram\Bot\Api $telegram
     */
    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Выполняет отправку сообщения в Telegram.
     *
     * @param \App\Contracts\Messages\FluentMessageContract $message
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function send(FluentMessageContract $message)
    {
        if (!$message->hasText() && !$message->hasAttachments()) {
            throw new InvalidArgumentException('Can not send empty message.');
        }

        if ($message->hasText()) {
            $this->sendMessage($message);
        }

        if (!$message->hasAttachments()) {
            return true;
        }

        collect($message->getAttachments())->each(function ($attachment, $type) use ($message) {
            $this->sendAttachment($message, $type, $attachment, $message->hasText());
        });

        return true;
    }

    /**
     * Выполняет запрос к API Telegram.
     *
     * @param string                     $method
     * @param \App\Contracts\BotContract $bot
     * @param array                      $payload
     *
     * @throws \RuntimeException
     *
     * @return \Illuminate\Support\Collection
     */
    public function performRequest(string $method, BotContract $bot, array $payload)
    {
        $response = null;

        try {
            $response = call_user_func_array([$this->telegram, $method], [$payload]);
        } catch (Exception $e) {
            Log::error('[TelegramTransport@performRequest] Telegram API: request failed.', [
                'exception' => $e,
                'method' => $method,
                'bot_id' => $bot->id(),
                'payload' => $payload,
            ]);

            throw new RuntimeException('Telegram API Request failed. See logs for more info.');
        }

        return $response;
    }

    /**
     * Отправляет базовый медиа-объект.
     *
     * @param string                                        $type
     * @param \App\Contracts\Messages\FluentMessageContract $message
     * @param array                                         $fields
     * @param bool                                          $disableNotification = false
     *
     * @return \Illuminate\Support\Collection
     */
    public function sendBaseAttachment(string $type, FluentMessageContract $message, array $fields, bool $disableNotification = false)
    {
        $payload = [
            'chat_id' => $message->getChannel()->chatIdentifier(),
            'disable_notification' => $disableNotification,
        ];

        return $this->performRequest('send'.$type, $message->getBot(), array_merge($payload, $fields));
    }

    /**
     * Выполняет отправку текстового сообщения.
     *
     * @param \App\Contracts\Messages\FluentMessageContract $message
     *
     * @return \Illuminate\Support\Collection
     */
    public function sendMessage(FluentMessageContract $message)
    {
        return $this->sendBaseAttachment('Message', $message, [
            'text' => $message->getText(),
            'parse_mode' => 'markdown',
        ]);
    }

    /**
     * Выполняет отправку прикрепления.
     *
     * @param \App\Contracts\Messages\FluentMessageContract $message
     * @param string                                        $type
     * @param mixed                                         $attachment
     * @param bool                                          $disableNotification = false
     *
     * @return \Illuminate\Support\Collection
     */
    public function sendAttachment(FluentMessageContract $message, string $type, $attachment, bool $disableNotification = false)
    {
        $method = 'send'.Str::studly($type);

        return call_user_func_array([$this, $method], [$message, $attachment, $disableNotification]);
    }

    /**
     * Выполняет отправку фото.
     *
     * @param \App\Contracts\Messages\FluentMessageContract $message
     * @param string                                        $photoUrl
     * @param bool                                          $disableNotification = false
     *
     * @return \Illuminate\Support\Collection
     */
    public function sendPhoto(FluentMessageContract $message, string $photoUrl, bool $disableNotification = false)
    {
        return $this->sendBaseAttachment('Photo', $message, ['photo' => $photoUrl], $disableNotification);
    }

    /**
     * Выполняет отправку аудио.
     *
     * @param \App\Contracts\Messages\FluentMessageContract $message
     * @param string                                        $audioUrl
     * @param bool                                          $disableNotification = false
     *
     * @return \Illuminate\Support\Collection
     */
    public function sendAudio(FluentMessageContract $message, string $audioUrl, bool $disableNotification = false)
    {
        return $this->sendBaseAttachment('Audio', $message, ['audio' => $audioUrl], $disableNotification);
    }

    /**
     * Выполняет отправку видео.
     *
     * @param \App\Contracts\Messages\FluentMessageContract $message
     * @param string                                        $videoUrl
     * @param bool                                          $disableNotification = false
     *
     * @return \Illuminate\Support\Collection
     */
    public function sendVideo(FluentMessageContract $message, string $videoUrl, bool $disableNotification = false)
    {
        return $this->sendBaseAttachment('Video', $message, ['video' => $videoUrl], $disableNotification);
    }

    /**
     * Выполняет отправку местоположения.
     *
     * @param \App\Contracts\Messages\FluentMessageContract $message
     * @param array                                         $coords
     * @param bool                                          $disableNotification = false
     *
     * @return \Illuminate\Support\Collection
     */
    public function sendLocation(FluentMessageContract $message, array $coords, bool $disableNotification = false)
    {
        $fields = [
            'latitude' => $coords[0],
            'longitude' => $coords[1],
        ];

        return $this->sendBaseAttachment('Location', $message, $fields, $disableNotification);
    }
}
