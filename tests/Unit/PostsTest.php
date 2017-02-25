<?php

namespace Tests\Unit;

use App\Bot;
use Mockery;
use App\Post;
use App\User;
use App\Channel;
use Carbon\Carbon;
use Tests\TestCase;
use Telegram\Bot\Api;
use App\Posts\Publisher;
use App\Jobs\PublishScheduledPost;
use Illuminate\Support\Facades\Bus;
use App\Transports\TelegramTransport;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostsTest extends TestCase
{
    use DatabaseMigrations;

    protected function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    public function testPostCanBePublished()
    {
        // Записи могут быть опубликованы.

        // Создаем бота, канал, пользователя.
        // Создаем мок Telegram API, создаем Telegram-транспорт с API.
        // Создаем объект Publisher, передаем в него Telegram-транспорт.
        // Создаем запись, указываем бот и канал, публикуем запись.
        // Проверяем, что запись была опубликована.

        $bot = factory(Bot::class)->create(['token' => 'token']);
        $channel = factory(Channel::class)->create(['chat_id' => '@telegram']);
        $user = factory(User::class)->create();

        $telegram = Mockery::mock(Api::class);

        $telegram->shouldReceive('sendMessage')
            ->with([
                'chat_id' => '@telegram',
                'disable_notification' => false,
                'text' => 'Hello world',
                'parse_mode' => 'markdown',
            ]);

        $transport = new TelegramTransport($telegram);
        $publisher = new Publisher($transport);

        $post = factory(Post::class)->create(['message' => 'Hello world']);
        $post->shouldBePublishedWith($bot, $channel);

        $publisher->publish($post, $user);
        $this->assertTrue($post->fresh()->wasPublished());
    }

    public function testScheduledPostPublishingJobShouldBeDispatchFromConsoleCommand()
    {
        // Задача публикации отложенных записей должна быть вызвана из консольной команды.

        // Создаем отложенную запись.
        // Вызываем команду публикации отложенных записей.
        // Проверяем, что задача публикации записи была вызвана.

        Bus::fake();

        $post = factory(Post::class)->create([
            'scheduled_at' => Carbon::now(),
        ]);

        Artisan::call('posts:publish');

        Bus::assertDispatched(PublishScheduledPost::class, function ($job) use ($post) {
            return $job->post->id === $post->id;
        });

        // Workaround for phpunit 6's risky test warning.
        $this->assertTrue(true);
    }

    public function testConsoleCommandShouldNotProcessPostsThatAreNotYetReadyToBePublished()
    {
        // Команда публикации отложенных записей не должна обрабатывать записи,
        // время публикации которых еще не подошло.

        // Создаем запись, которая должна быть опубликована через 2 часа.
        // Вызываем команду публикации отложенных записей.
        // Проверяем, что ни одна запись не была опубликована.

        $post = factory(Post::class)->create([
            'scheduled_at' => Carbon::now()->addHours(1),
        ]);

        Bus::fake();

        Artisan::call('posts:publish');

        Bus::assertNotDispatched(PublishScheduledPost::class);

        // Workaround for phpunit 6's risky test warning.
        $this->assertTrue(true);
    }
}
