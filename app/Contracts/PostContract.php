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
     * Прикрепляет медиа к записи.
     *
     * @param array $attachments
     *
     * @return \App\Contracts\PostContract
     */
    public function setAttachments(array $attachments);

    /**
     * Удаляет все прикрепления от записи.
     *
     * @return \App\Contracts\PostContract
     */
    public function removeAttachments();

    /**
     * Устанавливает бота и канал для этой записи.
     *
     * @param \App\Contracts\BotContract     $bot
     * @param \App\Contracts\ChannelContract $channel
     *
     * @return \App\Contracts\PostContract
     */
    public function shouldBePublishedWith(BotContract $bot, ChannelContract $channel);

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
