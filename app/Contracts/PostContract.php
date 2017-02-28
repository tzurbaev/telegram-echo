<?php

namespace App\Contracts;

use Carbon\Carbon;

interface PostContract
{
    /**
     * Возвращает бота, с помощью которого будет публиковаться эта запись.
     *
     * @return \App\Contracts\BotContract
     */
    public function getBot();

    /**
     * Возвращает канал, в который будет опубликована эта запись.
     *
     * @return \App\Contracts\ChannelContract
     */
    public function getChannel();

    /**
     * Возвращает владельца публикации.
     *
     * @return \App\Contracts\UserContract
     */
    public function getOwner();

    /**
     * Текст записи.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Прикрепляет медиа к записи.
     *
     * @param array $attachments
     * @param bool  $autosave    = true
     *
     * @return \App\Contracts\PostContract
     */
    public function setAttachments(array $attachments, bool $autosave = true);

    /**
     * Обновляет прикреления или удаляет их.
     *
     * @param mixed $attachments
     * @param bool  $remove      = false
     * @param bool  $autosave    = true
     *
     * @return \App\Contracts\PostContract
     */
    public function updateOrRemoveAttachments($attachments, bool $remove = false, bool $autosave = true);

    /**
     * Удаляет все прикрепления от записи.
     *
     * @param bool $autosave = true
     *
     * @return \App\Contracts\PostContract
     */
    public function removeAttachments(bool $autosave = true);

    /**
     * Устанавливает бота и канал для этой записи.
     *
     * @param \App\Contracts\BotContract     $bot
     * @param \App\Contracts\ChannelContract $channel
     * @param bool                           $autosave = true
     *
     * @return \App\Contracts\PostContract
     */
    public function shouldBePublishedWith(BotContract $bot, ChannelContract $channel, bool $autosave = true);

    /**
     * Проверяет, является ли запись отложенной.
     *
     * @return bool
     */
    public function isScheduled(): bool;

    /**
     * Проверяет, можно ли опубликовать запись прямо сейчас.
     *
     * @return bool
     */
    public function canBePublishedNow(): bool;

    /**
     * Проверяет, была ли запись уже опубликована.
     *
     * @return bool
     */
    public function wasPublished(): bool;

    /**
     * Проверяет, есть ли у записи текст.
     *
     * @return bool
     */
    public function hasMessage(): bool;

    /**
     * Проверяет, есть ли у записи прикрепления.
     *
     * @return bool
     */
    public function hasAttachments();

    /**
     * Возвращает коллекцию прикрепленных медиа.
     *
     * @return \Illuminate\Support\Collection
     */
    public function attachmentsCollection();

    /**
     * Устанавливает дату публикации записи.
     *
     * @param \Carbon\Carbon $at
     *
     * @return \App\Contracts\PostContract
     */
    public function setPublished(Carbon $at);
}
