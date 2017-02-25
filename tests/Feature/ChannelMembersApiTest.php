<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Contracts\Channels\ChannelsFactoryContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelMembersApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testUsersCanBeAddedToChannelsViaApi()
    {
        // Пользователи могут быть добавлены в список участников канала с помощью API.

        // Создаем пользователя, создаем канал.
        // Создаем второго пользователя.
        // Авторизуемся под первым пользователем.
        // Вызываем метод добавления второго пользователя в канал.
        // Проверяем, что второй пользователь был добавлен в список участников канала.

        $user = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel', '@telegram');

        $secondUser = factory(User::class)->create();

        $this->actingAs($user);

        $payload = [
            'user_id' => $secondUser->id,
        ];

        $this->json('POST', route('api.channels.members.store', ['channel' => $channel->id]), $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $secondUser->id,
            ]);
    }

    public function testUsersCanBeRemovedFromChannelsViaApi()
    {
        // Пользователи могут быть удалены из списка участников канала с помощью API.

        // Создаем пользователя, создаем канал.
        // Создаем второго пользователя.
        // Добавляем второго пользователя в канал.
        // Авторизуемся под первым пользователем.
        // Вызываем метод удаления второго пользователя из канала.
        // Проверяем, что второй пользователь был удален из списка участников канала.

        $user = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel', '@telegram');

        $secondUser = factory(User::class)->create();
        $channel->addMember($secondUser);

        $this->actingAs($user);

        $payload = [
            'user_id' => $secondUser->id,
        ];

        $this->json('DELETE', route('api.channels.members.destroy', ['channel' => $channel->id]), $payload)
            ->assertStatus(200);
    }
}
