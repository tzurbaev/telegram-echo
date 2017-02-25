<?php

namespace Tests\Feature;

use App\Bot;
use App\Post;
use App\User;
use App\Channel;
use Tests\TestCase;
use App\Jobs\PublishScheduledPost;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostsApiTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        Event::fake();
    }

    public function testPostsCanBeCreatedViaApi()
    {
        // Записи могут быть созданы с помощью API.

        // Создаем пользователя, создаем бота, создаем канал.
        // Авторизуемся.
        // Выполняем запрос на создание записи.
        // Проверяем, что запись была создана.

        Bus::fake();

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);
        $channel = factory(Channel::class)->create(['bot_id' => $bot->id, 'user_id' => $user->id]);
        $channel->addMember($user);

        $this->actingAs($user);

        $payload = [
            'channel_id' => $channel->id,
            'message' => 'Hello world',
            'attachments' => [
                ['type' => 'photo', 'params' => ['https://example.org/example.jpg']],
            ],
        ];

        $this->json('POST', route('api.posts.store'), $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => 1,
                'publication_dispatched' => 1,
            ]);

        Bus::assertDispatched(PublishScheduledPost::class);
    }

    public function testPostsListCanBeAccessedViaApi()
    {
        // Список записей может быть получен с помощью API.

        // Создаем пользователя, создаем бота, создаем канал.
        // Создаем 2 записи.
        // Авторизуемся.
        // Выполняем запрос на получение списка записей.
        // Проверяем, что обе записи доступны в ответе API.

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);
        $channel = factory(Channel::class)->create(['bot_id' => $bot->id, 'user_id' => $user->id]);
        $channel->addMember($user);

        $firstPost = factory(Post::class)->create([
            'user_id' => $user->id,
            'message' => 'Hello world from post 1',
            'scheduled_at' => null,
            'published_at' => null,
        ]);

        $secondPost = factory(Post::class)->create([
            'user_id' => $user->id,
            'message' => 'Hello world from post 2',
            'scheduled_at' => null,
            'published_at' => null,
        ]);

        $firstPost->shouldBePublishedWith($bot, $channel);
        $secondPost->shouldBePublishedWith($bot, $channel);

        $this->actingAs($user);

        $this->json('GET', route('api.posts.index'))
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Hello world from post 1',
            ])
            ->assertJsonFragment([
                'message' => 'Hello world from post 2',
            ]);
    }

    public function testSinglePostCanBeAccessedViaApi()
    {
        // Одиночный экземпляр записи может быть получен с помощью API.

        // Создаем пользователя, создаем бота, создаем канал.
        // Создаем запись.
        // Авторизуемся.
        // Выполняем запрос на получение записи.
        // Проверяем, что запись доступна в ответе API.

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);
        $channel = factory(Channel::class)->create(['bot_id' => $bot->id, 'user_id' => $user->id]);
        $channel->addMember($user);

        $post = factory(Post::class)->create([
            'user_id' => $user->id,
            'message' => 'Hello world',
            'scheduled_at' => null,
            'published_at' => null,
        ]);

        $post->shouldBePublishedWith($bot, $channel);

        $this->actingAs($user);

        $this->json('GET', route('api.posts.show', ['post' => $post->id]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
            ]);
    }

    public function testPostsCanBeUpdatedViaApi()
    {
        // Запись может быть обновлена через API.

        // Создаем пользователя, создаем бота, создаем канал.
        // Создаем запись.
        // Авторизуемся.
        // Выполняем запрос обновления записи.
        // Проверяем, что запись была изменена с помощью API.

        Bus::fake();

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);
        $channel = factory(Channel::class)->create(['bot_id' => $bot->id, 'user_id' => $user->id]);
        $channel->addMember($user);

        $post = factory(Post::class)->create([
            'user_id' => $user->id,
            'message' => 'Hello world',
            'scheduled_at' => null,
            'published_at' => null,
        ]);

        $post->shouldBePublishedWith($bot, $channel);

        $this->actingAs($user);

        $payload = [
            'message' => 'Example text',
        ];

        $this->json('PUT', route('api.posts.update', ['post' => $post->id]), $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => 'Example text',
            ]);

        Bus::assertDispatched(PublishScheduledPost::class);
    }

    public function testpostsCanBeDeletedViaApi()
    {
        // Запись может быть удален через API.

        // Создаем пользователя, создаем бота, создаем канал.
        // Создаем запись.
        // Авторизуемся.
        // Вызываем метод удаления записи.
        // Проверяем, что запись была удалена с помощью API.

        $user = factory(User::class)->create();
        $bot = factory(Bot::class)->create(['user_id' => $user->id]);
        $channel = factory(Channel::class)->create(['bot_id' => $bot->id, 'user_id' => $user->id]);
        $channel->addMember($user);

        $post = factory(Post::class)->create([
            'user_id' => $user->id,
            'message' => 'Hello world',
            'scheduled_at' => null,
            'published_at' => null,
        ]);

        $post->shouldBePublishedWith($bot, $channel);

        $this->actingAs($user);

        $this->json('DELETE', route('api.posts.destroy', ['post' => $post->id]))
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => 1,
            ]);

        $this->assertNull(Post::find($post->id));
    }
}
