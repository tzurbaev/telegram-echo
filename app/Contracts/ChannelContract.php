<?php

namespace App\Contracts;

interface ChannelContract
{
    /**
     * ID канала.
     *
     * @return int
     */
    public function id(): int;

    /**
     * Проверяет, привязан ли бот к каналу.
     *
     * @return bool
     */
    public function hasBot(): bool;

    /**
     * Идентификатор чата.
     *
     * @return int|string
     */
    public function chatIdentifier();

    /**
     * Список участников канала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members();

    /**
     * Количество участников канала.
     *
     * @return int
     */
    public function membersCount(): int;

    /**
     * Проверяет, является ли переданный пользователь участником канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @return bool
     */
    public function hasMember(UserContract $user): bool;

    /**
     * Проверяет, является ли переданный пользователь создателем канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @return bool
     */
    public function isCreator(UserContract $user): bool;

    /**
     * Добавляет пользователя в список участников канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @throws \InvalidArgumentException
     *
     * @return \App\Contracts\ChannelContract
     */
    public function addMember(UserContract $user);

    /**
     * Удаляет пользователя из списка участников канала.
     *
     * @param \App\Contracts\UserContract $user
     *
     * @return \App\Contracts\ChannelContract
     */
    public function removeMember(UserContract $user);
}
