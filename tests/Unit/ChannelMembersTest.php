<?php

namespace Tests\Unit;

use App\User;
use App\Channel;
use Tests\TestCase;
use InvalidArgumentException;
use App\Contracts\Channels\ChannelsFactoryContract;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelMembersTest extends TestCase
{
    use DatabaseMigrations;

    public function testChannelCreatorShouldBeAutoAddedToChannelAfterCreation()
    {
        // Создатель канала должен быть добавлен в список участников сразу после создания канала.

        // Создаем пользователя, создаем канал.
        // Проверяем, что пользователь добавлен в список участников канала.

        $user = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel');

        $this->assertInstanceOf(Channel::class, $channel);
        $this->assertTrue($channel->hasMember($user));
    }

    public function testUsersCanBeAddedToChannelMembersList()
    {
        // Пользователи могут быть добавлены в список участников канала вручную.

        // Создаем пользователя, создаем канал.
        // Проверяем, что количество участников канала равно единице.
        // Создаем второго пользователя, добавляем его в канал.
        // Проверяем, что количество участников канала равно двум.

        $user = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel');
        $this->assertSame(1, $channel->membersCount());

        $channel->addMember($secondUser);
        $this->assertSame(2, $channel->membersCount());
    }

    public function testUsersCanBeRemovedFromChannelMembersList()
    {
        // Пользователи могут быть удалены из списка участников канала.

        // Создаем пользователя, создаем канал.
        // Создаем второго пользователя, добавляем его в канал.
        // Проверяем, что количество участников канала равно двум.
        // Удаляем второго пользователя из канала.
        // Проверяем, что количество участников канала равно единице.

        $user = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel');

        $channel->addMember($secondUser);
        $this->assertSame(2, $channel->membersCount());

        $channel->removeMember($secondUser);
        $this->assertSame(1, $channel->membersCount());
    }

    public function testExceptionShouldBeThrownWhenAddingUserThatAlreadyListedAsChannelMember()
    {
        // Во время добавления пользователя, который уже является членом канала, в этот же канал
        // должно быть выброшено исключение.

        // Создаем пользователя, создаем канал.
        // Пытаемся добавить этого же пользователя в тот же самый канал.
        // Проверяем, что было выброшено исключение.

        $user = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel');

        $this->expectException(InvalidArgumentException::class);
        $channel->addMember($user);
    }

    public function testChannelShouldBeAbleToDetermineChannelCreator()
    {
        // Канал должен корректно определять, является ли пользователь создателем канала.

        // Создаем пользователя, создаем канал.
        // Проверяем, что канал корректно определил, что первый пользователь является создателем.
        // Создаем второго пользователя.
        // Проверяем, что канал корректно определил, что второй пользователь не является создателем.

        $user = factory(User::class)->create();
        $secondUser = factory(User::class)->create();
        $channels = app(ChannelsFactoryContract::class);

        $channel = $channels->make($user, 'My Channel');

        $this->assertTrue($channel->isCreator($user));
        $this->assertFalse($channel->isCreator($secondUser));
    }
}
