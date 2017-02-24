<?php

namespace Tests\Unit;

use App\Bot;
use Mockery;
use App\Post;
use App\User;
use App\Channel;
use Tests\TestCase;
use Telegram\Bot\Api;
use App\Posts\Publisher;
use App\Transports\TelegramTransport;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostsTest extends TestCase
{
    use DatabaseMigrations;

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testPostCanBePostedRightAfterCreation()
    {
        // Записи могут быть опубликованы сразу после создания.

        // Создаем запись, публикуем.
        // Проверяем, что запись была опубликована.

        $bot = factory(Bot::class)->create(['token' => 'token']);
        $channel = factory(Channel::class)->create(['chat_id' => '@telegram']);
        $user = factory(User::class)->create();
        $channel->addMember($user);

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
}
