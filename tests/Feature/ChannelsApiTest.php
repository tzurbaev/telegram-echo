<?php

namespace Tests\Feature;

use App\Bot;
use App\User;
use App\Channel;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use App\Contracts\Channels\ChannelsFactoryContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelsApiTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    public function testChannelsCanBeCreatedViaApi()
    {
        // Каналы могут быть созданы через API.

        // Создаем пользователя, авторизуемся.
        // Создаем бота для привязки к каналу.
        // Вызываем метод создания нового канала.
        // Проверяем, что канал был создан.

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $payload = [
            'name' => 'Telegram Channel',
            'chat_id' => '@telegram',
            'bot_id' => $bot->id,
        ];

        $this->json('POST', route('api.channels.store'), $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => 1,
            ]);

        $channel = Channel::where('chat_id', '@telegram')->first();
        $this->assertInstanceOf(Channel::class, $channel);
        $this->assertSame($user->id, $channel->user_id);
    }

    public function testChannelsListCanBeAccessedViaApi()
    {
        // Список каналов может быть получен через API.

        // Создаем пользователя, создаем 2 канала.
        // Вызываем метод для получения списка каналов.
        // Проверяем, что оба канала доступны в ответе API.

        $user = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $firstChannel = $channels->make($user, 'My Channel', '@telegram');
        $secondChannel = $channels->make($user, 'My Second Channel', '@channel');

        $this->actingAs($user);

        $this->json('GET', route('api.channels.index'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $firstChannel->id,
            ])
            ->assertJsonFragment([
                'id' => $secondChannel->id,
            ]);
    }

    public function testSingleChannelCanBeAccessedViaApi()
    {
        // Одиночный экземпляр канала может быть получен через API.

        // Создаем пользователя, создаем канал.
        // Вызываем метод получения информации и канале.
        // Проверяем, что канал доступен в ответе API.

        $user = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel', '@telegram');

        $this->actingAs($user);

        $this->json('GET', route('api.channels.show', ['channel' => $channel->id]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $channel->id,
            ]);
    }

    public function testChannelCanBeUpdatedViaApi()
    {
        // Канал может быть обновлен через API.

        // Создаем пользователя, создаем канал.
        // Вызываем метод обновления канала.
        // Проверяем, что канал был изменен с помощью API.

        $user = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel', '@telegram');

        $this->actingAs($user);

        $payload = [
            'name' => 'Telegram Echo',
        ];

        $this->json('PUT', route('api.channels.update', ['channel' => $channel->id]), $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Telegram Echo',
            ]);

        $this->assertSame('Telegram Echo', $channel->fresh()->name);
    }

    public function testChannelCanBeDeletedViaApi()
    {
        // Канал может быть удален через API.

        // Создаем пользователя, создаем канал.
        // Вызываем метод удаления канала.
        // Проверяем, что канал был удален с помощью API.

        $user = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel', '@telegram');

        $this->actingAs($user);

        $this->json('DELETE', route('api.channels.destroy', ['channel' => $channel->id]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => 1,
            ]);

        $this->assertNull(Channel::find($channel->id));
    }
}
