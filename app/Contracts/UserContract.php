<?php

namespace App\Contracts;

interface UserContract
{
    /**
     * ID пользователя.
     *
     * @return int
     */
    public function id(): int;

    /**
     * Email пользователя.
     *
     * @return string
     */
    public function email(): string;

    /**
     * Имя пользователя.
     *
     * @return string
     */
    public function name(): string;

    /**
     * Каналы, созданные пользователем.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels();

    /**
     * Боты, созданные пользователем.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bots();

    /**
     * Записи, созданные пользователем.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts();
}
