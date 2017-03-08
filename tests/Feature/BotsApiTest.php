<?php

namespace Tests\Feature;

use App\Bot;
use App\User;
use Tests\TestCase;
use App\Events\Bots\BotCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BotsApiTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    public function testBotsCanBeCreatedViaApi()
    {
        // Боты могут быть созданы через API.

        // Создаем пользователя, авторизуемся.
        // Вызываем метод создания бота.
        // Проверяем, что бот был создан.

        $user = factory(User::class)->create();

        $this->actingAs($user);

        $payload = [
            'name' => 'My Bot',
            'username' => 'my_bot',
            'token' => '123:token',
        ];

        $this->json('POST', route('api.bots.store'), $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => 1,
            ]);

        $bot = Bot::orderBy('created_at', 'desc')->first();
        $this->assertSame($user->id, $bot->user_id);
        $this->assertSame($payload['token'], $bot->apiToken());

        Event::assertDispatched(BotCreated::class, function ($event) use ($bot) {
            return $event->bot->id === $bot->id;
        });
    }

    public function testBotsListCanBeAccessedViaApi()
    {
        // Список ботов может быть получен через API.

        // Создаем пользователя, создаем 2 бота.
        // Вызываем метод для получения списка ботов.
        // Проверяем, что оба бота доступны в ответе API.

        $user = factory(User::class)->create();

        $firstBot = factory(Bot::class)->create(['user_id' => $user->id]);
        $secondBot = factory(Bot::class)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->json('GET', route('api.bots.index'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $firstBot->id,
            ])
            ->assertJsonFragment([
                'id' => $secondBot->id,
            ]);
    }

    public function testSingleBotCanBeAccessedViaApi()
    {
        // Одиночный экземпляр бота может быть получен через API.

        // Создаем пользователя, создаем бота.
        // Вызываем метод получения информации и боте.
        // Проверяем, что бот доступен в ответе API.

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->json('GET', route('api.bots.show', ['bot' => $bot->id]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $bot->id,
            ]);
    }

    public function testBotCanBeUpdatedViaApi()
    {
        // Бот может быть обновлен через API.

        // Создаем пользователя, создаем бота.
        // Вызываем метод обновления бота.
        // Проверяем, что бот был изменен с помощью API.

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $payload = [
            'token' => '123:newtoken',
        ];

        $this->json('PUT', route('api.bots.update', ['bot' => $bot->id]), $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $bot->id,
            ]);

        $this->assertSame($payload['token'], $bot->fresh()->token);
    }

    public function testBotCanBeDeletedViaApi()
    {
        // Бот может быть удален через API.

        // Создаем пользователя, создаем бота.
        // Вызываем метод удаления бота.
        // Проверяем, что бот был удален с помощью API.

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $this->json('DELETE', route('api.bots.destroy', ['bot' => $bot->id]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => 1,
            ]);

        $this->assertNull(Bot::find($bot->id));
    }
}
