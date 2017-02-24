<?php

namespace Tests\Unit;

use App\Bot;
use Mockery;
use App\Channel;
use Tests\TestCase;
use Telegram\Bot\Api;
use App\Transports\TelegramTransport;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BotsTest extends TestCase
{
    use DatabaseMigrations;

    protected function tearDown()
    {
        Mockery::close();
    }

    public function testBotShouldBeAbleToSendTextMessages()
    {
        // Боты должны уметь отправлять текстовые сообщения.

        // Создаем бота, создаем канал.
        // Создаем мок Telegram API, создаем транспорт.
        // Отправляем текстовое сообщение в канал.
        // Проверяем, что сообщение было отправлено.

        $bot = factory(Bot::class)->create(['token' => 'token']);
        $channel = factory(Channel::class)->create(['chat_id' => '@telegram']);

        $telegram = Mockery::mock(Api::class);
        $telegram->shouldReceive('sendMessage')
            ->with([
                'chat_id' => '@telegram',
                'disable_notification' => false,
                'text' => 'Hello world',
                'parse_mode' => 'markdown',
            ]);

        $transport = new TelegramTransport($telegram);

        $message = $bot->broadcast('Hello world')->to($channel);
        $this->assertTrue($transport->send($message));
    }

    public function testBotShouldBeAbleToSendPhotoAttachments()
    {
        // Боты должны уметь отправлять фотографии.

        // Создаем бота, создаем канал.
        // Создаем мок Telegram API, создаем транспорт.
        // Отправляем сообщение с фотографией в канал.
        // Проверяем, что сообщение было отправлено.

        $bot = factory(Bot::class)->create(['token' => 'token']);
        $channel = factory(Channel::class)->create(['chat_id' => '@telegram']);

        $telegram = Mockery::mock(Api::class);
        $telegram->shouldReceive('sendPhoto')
            ->with([
                'chat_id' => '@telegram',
                'disable_notification' => false,
                'photo' => 'https://example.org/example.jpg',
            ]);

        $transport = new TelegramTransport($telegram);

        $message = $bot->broadcast()->to($channel)->withPhoto('https://example.org/example.jpg');
        $this->assertTrue($transport->send($message));
    }

    public function testBotShouldBeAbleToSendAudioAttachments()
    {
        // Боты должны уметь отправлять аудиофайлы.

        // Создаем бота, создаем канал.
        // Создаем мок Telegram API, создаем транспорт.
        // Отправляем сообщение с аудиофайлом в канал.
        // Проверяем, что сообщение было отправлено.

        $bot = factory(Bot::class)->create(['token' => 'token']);
        $channel = factory(Channel::class)->create(['chat_id' => '@telegram']);

        $telegram = Mockery::mock(Api::class);
        $telegram->shouldReceive('sendAudio')
            ->with([
                'chat_id' => '@telegram',
                'disable_notification' => false,
                'audio' => 'https://example.org/example.mp3',
            ]);

        $transport = new TelegramTransport($telegram);

        $message = $bot->broadcast()->to($channel)->withAudio('https://example.org/example.mp3');
        $this->assertTrue($transport->send($message));
    }

    public function testBotShouldBeAbleToSendVideoAttachments()
    {
        // Боты должны уметь отправлять видеофайлы.

        // Создаем бота, создаем канал.
        // Создаем мок Telegram API, создаем транспорт.
        // Отправляем сообщение с видеофайлом в канал.
        // Проверяем, что сообщение было отправлено.

        $bot = factory(Bot::class)->create(['token' => 'token']);
        $channel = factory(Channel::class)->create(['chat_id' => '@telegram']);

        $telegram = Mockery::mock(Api::class);
        $telegram->shouldReceive('sendVideo')
            ->with([
                'chat_id' => '@telegram',
                'disable_notification' => false,
                'video' => 'https://example.org/example.mp4',
            ]);

        $transport = new TelegramTransport($telegram);

        $message = $bot->broadcast()->to($channel)->withVideo('https://example.org/example.mp4');
        $this->assertTrue($transport->send($message));
    }

    public function testBotShouldBeAbleToSendLocationAttachments()
    {
        // Боты должны уметь отправлять местоположение.

        // Создаем бота, создаем канал.
        // Создаем мок Telegram API, создаем транспорт.
        // Отправляем сообщение с местоположением в канал.
        // Проверяем, что сообщение было отправлено.

        $bot = factory(Bot::class)->create(['token' => 'token']);
        $channel = factory(Channel::class)->create(['chat_id' => '@telegram']);

        $lat = 51.834868;
        $lon = 107.585534;

        $telegram = Mockery::mock(Api::class);
        $telegram->shouldReceive('sendLocation')
            ->with([
                'chat_id' => '@telegram',
                'disable_notification' => false,
                'latitude' => $lat,
                'longitude' => $lon,
            ]);

        $transport = new TelegramTransport($telegram);

        $message = $bot->broadcast()->to($channel)->withLocation($lat, $lon);
        $this->assertTrue($transport->send($message));
    }

    public function testBotShouldBeAbleToSendTextMessagesWithAttachments()
    {
        // Боты должны уметь отправлять текст и прикрепленные медиа.

        // Создаем бота, создаем канал.
        // Создаем мок Telegram API, создаем транспорт.
        // Отправляем сообщение с текстом и фотографией в канал.
        // Проверяем, что сообщение было отправлено.

        $bot = factory(Bot::class)->create(['token' => 'token']);
        $channel = factory(Channel::class)->create(['chat_id' => '@telegram']);

        $telegram = Mockery::mock(Api::class);
        $telegram->shouldReceive('sendMessage')
            ->with([
                'chat_id' => '@telegram',
                'disable_notification' => false,
                'text' => 'Hello world',
                'parse_mode' => 'markdown',
            ]);

        $telegram->shouldReceive('sendPhoto')
            ->with([
                'chat_id' => '@telegram',
                'disable_notification' => true,
                'photo' => 'https://example.org/example.jpg',
            ]);

        $transport = new TelegramTransport($telegram);

        $message = $bot->broadcast('Hello world')->to($channel)->withPhoto('https://example.org/example.jpg');
        $this->assertTrue($transport->send($message));
    }
}
