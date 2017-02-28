<?php

namespace App;

use App\Contracts\UserContract;
use App\Contracts\ChannelContract;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Channel extends Model implements ChannelContract
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'bot_id', 'name', 'slug', 'chat_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'user_id' => 'integer',
        'bot_id' => 'integer',
    ];

    /**
     * @var array
     */
    protected $with = ['members'];

    /**
     * ID канала.
     *
     * @return int
     */
    public function id(): int
    {
        return intval($this->attributes['id']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Проверяет, привязан ли бот к каналу.
     *
     * @return bool
     */
    public function hasBot(): bool
    {
        return intval($this->bot_id) !== 0;
    }

    /**
     * Возвращает привязанный бот.
     *
     * @return \App\Contracts\BotContract|null
     */
    public function getBot()
    {
        return $this->hasBot() ? $this->bot : null;
    }

    /**
     * Идентификатор чата.
     *
     * @return int|string
     */
    public function chatIdentifier()
    {
        return $this->chat_id;
    }

    /**
     * Список участников канала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Возвращает индекс переданного пользователя из коллекции участников канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @return int|bool
     */
    protected function getMemberIndex(UserContract $user)
    {
        return $this->members->search(function (UserContract $member) use ($user) {
            return $member->id() === $user->id();
        });
    }

    /**
     * Возвращает список участников канала.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMembers()
    {
        return $this->members;
    }

    /**
     * Количество участников канала.
     *
     * @return int
     */
    public function membersCount(): int
    {
        return count($this->members);
    }

    /**
     * Проверяет, является ли переданный пользователь участником канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @return bool
     */
    public function hasMember(UserContract $user): bool
    {
        return $this->getMemberIndex($user) !== false;
    }

    /**
     * Проверяет, является ли переданный пользователь создателем канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @return bool
     */
    public function isCreator(UserContract $user): bool
    {
        return $this->user_id === $user->id();
    }

    /**
     * Добавляет пользователя в список участников канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @throws \InvalidArgumentException
     *
     * @return \App\Contracts\ChannelContract
     */
    public function addMember(UserContract $user)
    {
        if ($this->hasMember($user)) {
            throw new InvalidArgumentException(
                'Given user '.$user->email().' is already member of '.$this->name.' ('.$this->id.') channel.'
            );
        }

        $this->members()->attach($user->id());
        $this->load('members');

        return $this;
    }

    /**
     * Удаляет пользователя из списка участников канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @return \App\Contracts\ChannelContract
     */
    public function removeMember(UserContract $user)
    {
        if (!$this->hasMember($user)) {
            return $this;
        }

        $this->members()->detach($user->id());
        $this->load('members');

        return $this;
    }
}
