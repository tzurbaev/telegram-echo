<?php

namespace App;

use App\Contracts\UserContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements UserContract
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'timezone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * ID пользователя.
     *
     * @return int
     */
    public function id(): int
    {
        return intval($this->attributes['id']);
    }

    /**
     * Email пользователя.
     *
     * @return string
     */
    public function email(): string
    {
        return $this->attributes['email'];
    }

    /**
     * Имя пользователя.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->attributes['name'];
    }

    /**
     * Каналы, созданные пользователем.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * Боты, созданные пользователем.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    /**
     * Записи, созданные пользователем.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
