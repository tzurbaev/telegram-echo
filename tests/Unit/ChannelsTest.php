<?php

namespace Tests\Unit;

use App\User;
use App\Channel;
use Tests\TestCase;
use App\Contracts\Channels\ChannelsFactoryContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelsTest extends TestCase
{
    use DatabaseMigrations;

    public function testChannelsCanBeCreatedViaFactory()
    {
        // Каналы могут быть созданы с помощью фабрики.

        // Создаем фабрику каналов.
        // Создаем пользователя, создаем канал с помощью фабрики.
        // Проверяем, что канал был создан и является экземпляром класса Channel.

        $channels = app(ChannelsFactoryContract::class);
        $user = factory(User::class)->create();

        $channel = $channels->make($user, 'My Channel');
        $this->assertInstanceOf(Channel::class, $channel);
    }
}
