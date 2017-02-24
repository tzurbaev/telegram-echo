<?php

namespace Tests\Unit;

use App\Bot;
use App\Channel;
use Tests\TestCase;
use App\Messages\FluentMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FluentMessagesTest extends TestCase
{
    use DatabaseMigrations;

    public function testMessageMayHaveBotAttached()
    {
        // К сообщению может быть привязан Telegram-бот.

        // Создаем бота, создаем новое сообщение с переданным ботом.
        // Проверяем, что ID бота из сообщения совпадает с ID оригинального бота.

        $bot = factory(Bot::class)->create();
        $message = new FluentMessage($bot);

        $this->assertInstanceOf(Bot::class, $message->getBot());
        $this->assertSame($message->getBot(), $bot);
    }

    public function testMessageMayHaveChannelAttached()
    {
        // К сообщению может быть привязан Telegram-канал.

        // Создаем канал, создаем новое сообщение с переданным каналом.
        // Проверяем, что ID канала из сообщения совпадает с ID оригинального канала.

        $channel = factory(Channel::class)->create();
        $message = new FluentMessage(null, $channel);

        $this->assertInstanceOf(Channel::class, $message->getChannel());
        $this->assertSame($message->getChannel(), $channel);
    }

    public function testMessageMayHaveChannelAttachedViaToMethod()
    {
        // К сообщению может быть привязан Telegram-канал с помощью метода 'to'.

        // Создаем новое сообщение.
        // Создаем канал, задаем канал сообщения.
        // Проверяем, что ID канала из сообщения совпадает с ID оригинального канала.

        $message = new FluentMessage();

        $channel = factory(Channel::class)->create();
        $message->to($channel);

        $this->assertInstanceOf(Channel::class, $message->getChannel());
        $this->assertSame($message->getChannel(), $channel);
    }

    public function testMessageMayHaveText()
    {
        // У сообщений может быть текст.

        // Создаем новое сообщение, задаём текст.
        // Проверяем, что сохраненный текст совпадает с первоначальным.

        $message = new FluentMessage();
        $text = 'Hello world';

        $message->say($text);
        $this->assertSame($text, $message->getText());
    }

    /**
     * @dataProvider messageAttachmentsDataProvider
     */
    public function testMessageMayHaveAttachments(string $type, array $args, $expected)
    {
        // У сообщения могут быть прикрепления.

        // Создаем новое сообщение.
        // Проверяем, что количество прикреплений равно 0.
        // Прикрепляем медиа.
        // Проверяем, что количество прикреплений равно 1.
        // Проверяем, что сохраненные данные прикрепления совпадают с оригинальными.

        $message = new FluentMessage();
        $this->assertSame(0, $message->attachmentsCount());

        call_user_func_array([$message, 'with'.$type], $args);
        $this->assertSame(1, $message->attachmentsCount());
        $this->assertSame($expected, $message->getAttachments()[strtolower($type)]);
    }

    public function messageAttachmentsDataProvider()
    {
        return [
            [
                'type' => 'Photo',
                'args' => ['https://example.org/example.jpg'],
                'expected' => 'https://example.org/example.jpg'
            ],
            [
                'type' => 'Video',
                'args' => ['https://example.org/example.mp4'],
                'expected' => 'https://example.org/example.mp4'
            ],
            [
                'type' => 'Audio',
                'args' => ['https://example.org/example.mp3'],
                'expected' => 'https://example.org/example.mp3'
            ],
            [
                'type' => 'Location',
                'args' => [12.5, 12.5],
                'expected' => [12.5, 12.5],
            ],
        ];
    }
}
