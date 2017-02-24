<?php

namespace App\Contracts\Messages;

use App\Contracts\ChannelContract;

interface FluentMessageContract
{
    /**
     * Бот, с помощью которого будет отправляться сообщение.
     *
     * @return \App\Contracts\BotContract
     */
    public function getBot();

    /**
     * Канал, в который будет отправлено сообщение.
     *
     * @return \App\Contracts\ChannelContract
     */
    public function getChannel();

    /**
     * Текст сообщения.
     *
     * @return string|null
     */
    public function getText();

    /**
     * Прикрепленные медиа.
     *
     * @return array
     */
    public function getAttachments();

    /**
     * Количество прикрепленных медиа.
     *
     * @return int
     */
    public function attachmentsCount(): int;

    /**
     * Проверяет, имеется ли у этого сообщения текст.
     *
     * @return bool
     */
    public function hasText(): bool;

    /**
     * Проверяет, имеются ли у этого сообщение прикрепленные медиа.
     *
     * @return bool
     */
    public function hasAttachments(): bool;

    /**
     * Указывает канал, в который будет отправлено сообщение.
     *
     * @param \App\Contracts\ChannelContract $channel
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function to(ChannelContract $channel);

    /**
     * Указывает какой текст отправить.
     *
     * @param string $text
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function say(string $text = null);

    /**
     * Прикрепляет аудио.
     *
     * @param string $audioUrl
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function withAudio(string $audioUrl);

    /**
     * Прикрепляет фото.
     *
     * @param string $photoUrl
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function withPhoto(string $photoUrl);

    /**
     * Прикрепляет видео.
     *
     * @param string $videoUrl
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function withVideo(string $videoUrl);

    /**
     * Прикрепляет местоположение.
     *
     * @param float $lat
     * @param float $lon
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function withLocation(float $lat, float $lon);
}
