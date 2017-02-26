<?php

namespace App;

use App\Contracts\BotContract;
use App\Messages\FluentMessage;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model implements BotContract
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'external_id', 'name', 'username', 'token',
    ];

    /**
     * @var array
     */
    protected $hidden = ['token'];

    /**
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'external_id' => 'integer',
    ];

    /**
     * ID бота.
     *
     * @return int
     */
    public function id(): int
    {
        return intval($this->attributes['id']);
    }

    /**
     * Запускает отправку сообщения в канал.
     *
     * @param string $message = null
     *
     * @return \App\Contracts\Messages\FluentMessageContract
     */
    public function broadcast(string $message = null)
    {
        return (new FluentMessage($this))->say($message);
    }

    /**
     * API-токен бота.
     *
     * @return string
     */
    public function apiToken(): string
    {
        return $this->attributes['token'];
    }
}
