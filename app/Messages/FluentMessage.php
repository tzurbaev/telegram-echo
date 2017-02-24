<?php

namespace App\Messages;

use App\Contracts\BotContract;
use App\Contracts\ChannelContract;
use App\Contracts\Messages\FluentMessageContract;

class FluentMessage implements FluentMessageContract
{
    /**
     * @var \App\Contracts\BotContract
     */
    protected $bot;

    /**
     * @var \App\Contracts\ChannelContract
     */
    protected $channel;

    /**
     * @var string
     */
    protected $text = '';

    /**
     * Прикрепленные медиа-файлы. Будут отправлены вслед
     * за основным сообщением без уведомлений.
     *
     * @var array
     */
    protected $attachments = [];

    /**
     * @param \App\Contracts\BotContract     $bot     = null
     * @param \App\Contracts\ChannelContract $channel = null
     */
    public function __construct(BotContract $bot = null, ChannelContract $channel = null)
    {
        $this->bot = $bot;
        $this->channel = $channel;
    }

    /**
     * Бот, с помощью которого будет отправляться сообщение.
     *
     * @return \App\Contracts\BotContract
     */
    public function getBot()
    {
        return $this->bot;
    }

    /**
     * Канал, в который будет отправлено сообщение.
     *
     * @return \App\Contracts\ChannelContract
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Текст сообщения.
     *
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Прикрепленные медиа.
     *
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Количество прикрепленных медиа.
     *
     * @return int
     */
    public function attachmentsCount(): int
    {
        return count($this->getAttachments());
    }

    /**
     * Проверяет, имеется ли у этого сообщения текст.
     *
     * @return bool
     */
    public function hasText(): bool
    {
        return !empty($this->getText());
    }

    /**
     * Проверяет, имеются ли у этого сообщение прикрепленные медиа.
     *
     * @return bool
     */
    public function hasAttachments(): bool
    {
        return $this->attachmentsCount() > 0;
    }

    /**
     * Указывает канал, в который будет отправлено сообщение.
     *
     * @param \App\Contracts\ChannelContract $channel
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function to(ChannelContract $channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Указывает какой текст отправить.
     *
     * @param string $text
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function say(string $text = null)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Прикрепляет аудио.
     *
     * @param string $audioUrl
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function withAudio(string $audioUrl)
    {
        return $this->withAttachment('audio', $audioUrl);
    }

    /**
     * Прикрепляет фото.
     *
     * @param string $photoUrl
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function withPhoto(string $photoUrl)
    {
        return $this->withAttachment('photo', $photoUrl);
    }

    /**
     * Прикрепляет видео.
     *
     * @param string $videoUrl
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function withVideo(string $videoUrl)
    {
        return $this->withAttachment('video', $videoUrl);
    }

    /**
     * Прикрепляет местоположение.
     *
     * @param float $lat
     * @param float $lon
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function withLocation(float $lat, float $lon)
    {
        return $this->withAttachment('location', [$lat, $lon]);
    }

    /**
     * Прикрепляет медиа.
     *
     * @param string $type
     * @param mixed  $data
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    protected function withAttachment(string $type, $data)
    {
        $this->attachments[$type] = $data;

        return $this;
    }
}
