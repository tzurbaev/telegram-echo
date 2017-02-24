<?php

namespace App;

use Carbon\Carbon;
use App\Contracts\BotContract;
use App\Contracts\PostContract;
use App\Contracts\ChannelContract;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements PostContract
{
    protected $fillable = [
        'user_id', 'channel_id', 'bot_id', 'message',
        'attachments', 'scheduled_at', 'published_at',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'channel_id' => 'integer',
        'bot_id' => 'integer',
        'attachments' => 'array',
    ];

    protected $dates = [
        'scheduled_at', 'published_at', 'created_at', 'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Concerns\BelongsTo
     */
    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    /**
     * Возвращает бота, с помощью которого будет публиковаться эта запись.
     *
     * @return \App\Contracts\BotContract
     */
    public function getBot()
    {
        return $this->bot;
    }

    /**
     * Возвращает канал, в который будет опубликована эта запись.
     *
     * @return \App\Contracts\ChannelContract
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * Устанавливает бота и канал для этой записи.
     *
     * @param \App\Contracts\BotContract     $bot
     * @param \App\Contracts\ChannelContract $channel
     *
     * @return \App\Contracts\PostContract
     */
    public function shouldBePublishedWith(BotContract $bot, ChannelContract $channel)
    {
        $this->update([
            'bot_id' => $bot->id,
            'channel_id' => $channel->id,
        ]);

        // Перезагружаем связи (если необходимо), чтобы потребители,
        // которые используют методы getBot/getChannel, получили
        // свежие объекты сразу же после обновления ID связей.

        collect(['bot', 'channel'])->each(function ($relation) {
            if ($this->relationLoaded($relation)) {
                $this->load($relation);
            }
        });

        return $this;
    }

    /**
     * Проверяет, является ли запись отложенной.
     *
     * @return bool
     */
    public function isScheduled(): bool
    {
        return !is_null($this->scheduled_at);
    }

    /**
     * Проверяет, можно ли опубликовать запись прямо сейчас.
     *
     * @return bool
     */
    public function canBePublishedNow(): bool
    {
        if (!$this->isScheduled()) {
            return true;
        }

        return $this->scheduled_at > Carbon::now();
    }

    /**
     * Проверяет, была ли запись уже опубликована.
     */
    public function wasPublished(): bool
    {
        return !is_null($this->published_at);
    }

    /**
     * Проверяет, есть ли у записи текст.
     *
     * @return bool
     */
    public function hasMessage(): bool
    {
        return !is_null($this->message);
    }

    /**
     * Проверяет, есть ли у записи прикрепления.
     *
     * @return bool
     */
    public function hasAttachments()
    {
        return is_array($this->attachments) && count($this->attachments) > 0;
    }

    /**
     * Возвращает коллекцию прикрепленных медиа.
     *
     * @return \Illuminate\Support\Collection
     */
    public function attachmentsCollection()
    {
        if (is_null($this->attachments)) {
            return collect([]);
        }

        return collect($this->attachments);
    }

    /**
     * Устанавливает дату публикации записи.
     *
     * @param \Carbon\Carbon $at
     *
     * @return \App\Contracts\PostContract
     */
    public function setPublished(Carbon $at)
    {
        $this->update(['published_at' => $at]);

        return $this;
    }
}
