<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;

class CustomValidatorsTest extends TestCase
{
    /**
     * @dataProvider botTokenRuleDataProvider
     */
    public function testBotTokenRuleTest(string $type, string $value, bool $shouldPass)
    {
        // Правило "bot_token" должно корректно проходить валидацию.

        // Создаем валидатор с указанными параметрами.
        // Проводим валидацию с указанным значением.

        // Проверяем, что валидация была пройдена или не пройдена.

        $validator = Validator::make(['token' => $value], [
            'token' => 'bot_token:'.$type,
        ]);

        $this->assertSame($shouldPass, $validator->passes());
    }

    /**
     * @dataProvider chatIdentifierRuleDataProvider
     */
    public function testChatIdentifierRuleTest(string $type, string $value, bool $shouldPass)
    {
        // Правило "chat_identifier" должно корректно проходить валидацию.

        // Создаем валидатор с указанными параметрами.
        // Проводим валидацию с указанным значением.

        // Проверяем, что валидация была пройдена или не пройдена.

        $validator = Validator::make(['token' => $value], [
            'token' => 'chat_identifier:'.$type,
        ]);

        $this->assertSame($shouldPass, $validator->passes());
    }

    public function botTokenRuleDataProvider()
    {
        return [
            [
                'type' => 'telegram',
                'value' => 'not a token',
                'shouldPass' => false,
            ],
            [
                'type' => 'telegram',
                'value' => 'wrong:token',
                'shouldPass' => false,
            ],
            [
                'type' => 'telegram',
                'value' => '123:token',
                'shouldPass' => true,
            ],
        ];
    }

    public function chatIdentifierRuleDataProvider()
    {
        return [
            [
                'type' => 'telegram',
                'value' => 'not a username',
                'shouldPass' => false,
            ],
            [
                'type' => 'telegram',
                'value' => '@correct',
                'shouldPass' => true,
            ],
            [
                'type' => 'telegram',
                'value' => 'https://t.me/correct',
                'shouldPass' => true,
            ],
            [
                'type' => 'telegram',
                'value' => 't.me/correct',
                'shouldPass' => true,
            ],
            [
                'type' => 'telegram',
                'value' => 'https://telegram.me/correct',
                'shouldPass' => true,
            ],
            [
                'type' => 'telegram',
                'value' => 'telegram.me/correct',
                'shouldPass' => true,
            ],
            [
                'type' => 'telegram',
                'value' => '_incorrect',
                'shouldPass' => false,
            ],
            [
                'type' => 'telegram',
                'value' => 'incorrect_',
                'shouldPass' => false,
            ],
            [
                'type' => 'telegram',
                'value' => '1incorrect',
                'shouldPass' => false,
            ],
            [
                'type' => 'telegram',
                'value' => '1incorrect_',
                'shouldPass' => false,
            ],
        ];
    }
}
