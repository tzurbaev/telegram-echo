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

    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    public function bots()
    {
        return $this->hasMany(Bot::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
