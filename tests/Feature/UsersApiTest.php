<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendTelegramNotification;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PasswordChangedNotification;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testNotificationsShouldBeSentAfterRegistration()
    {
        // После регистрации пользователя должны быть отправлены уведомления пользователю и администраторам.

        // Регистрируем нового пользователя.
        // Проверяем, что пользователь был зарегистрирован.
        // Проверяем, что welcome-письмо было отправлено.
        // Проверяем, что уведомление о регистрации было отправлено.

        Notification::fake();
        Bus::fake();

        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.org',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        $response = $this->call('POST', route('register'), $payload);
        $response->assertRedirect(route('dashboard'));

        $user = User::orderBy('created_at', 'desc')->first();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('john@example.org', $user->email);

        Notification::assertSentTo([$user], WelcomeNotification::class);

        Bus::assertDispatched(SendTelegramNotification::class, function ($job) use ($user) {
            return $job->hash === 'users.registered.'.$user->id;
        });
    }

    public function testNotificationShouldBeSentAfterUserChangesPassword()
    {
        // После смены пароля пользователю должно отправляться соответствующее уведомление.

        // Создаем пользователя, авторизуемся.
        // Вызываем API-метод для смены пароля.
        // Проверяем, что пароль был изменен.
        // Проверяем, что пользователю было отправлено уведомление.

        Notification::fake();

        $user = factory(User::class)->create([
            'name' => 'John Doe',
            'email' => 'john@example.org',
            'password' => bcrypt('secret'),
        ]);

        $this->assertTrue(Hash::check('secret', $user->password));

        $this->actingAs($user);

        $payload = [
            'current_password' => 'secret',
            'password' => 'my-password',
            'password_confirmation' => 'my-password',
        ];

        $this->json('PUT', route('api.settings.update'), $payload)
            ->assertStatus(200)
            ->assertJsonFragment([
                'success' => 1,
            ]);

        $user = $user->fresh();

        $this->assertFalse(Hash::check('secret', $user->password));
        $this->assertTrue(Hash::check('my-password', $user->password));

        Notification::assertSentTo([$user], PasswordChangedNotification::class);
    }
}
