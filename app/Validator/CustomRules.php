<?php

namespace App\Validator;

class CustomRules
{
    /**
     * Проводит валидацию правила "bot_token:provider".
     *
     * @param mixed $value
     * @param array $parameters
     *
     * @return bool
     */
    public function validateBotTokenRule($value, array $parameters): bool
    {
        if (in_array('telegram', $parameters)) {
            return $this->validateTelegramToken($value);
        }

        return false;
    }

    /**
     * Проводит валидацию правила "chat_identifier:provider".
     *
     * @param mixed $value
     * @param array $parameters
     *
     * @return bool
     */
    public function validateChatIdentifierRule($value, array $parameters): bool
    {
        if (in_array('telegram', $parameters)) {
            return $this->validateTelegramChatIdentifier($value);
        }

        return false;
    }

    /**
     * Проводит валидацию токена Telegram.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function validateTelegramToken($value): bool
    {
        $token = explode(':', $value);

        if (count($token) !== 2 || !is_numeric($token[0])) {
            return false;
        }

        return true;
    }

    /**
     * Проводит валидацию идентификатора чата в Telegram.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function validateTelegramChatIdentifier($value): bool
    {
        return $this->validateTelegramUsername($value);
    }

    /**
     * Проводит валидацию юзернейма в Telegram.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function validateTelegramUsername($value): bool
    {
        $value = $this->sanitizeTelegramUsername($value);

        // Корректный юзернейм не может начинаться с символов
        // подчеркивания, тире или цифр. Кроме того, он не
        // может заканчиваться на тире или подчеркивания.

        if (preg_match('/^[_\.\-0-9]/', $value) || preg_match('/[_\.\-]$/', $value)) {
            return false;
        }

        // Если в юзернейме присутствуют символы, отличные от
        // латинского алфавита, подчеркиваний, тире и цифр,
        // то мы имеем дело с неправильным значением.

        if (!preg_match('/^[a-zA-Z0-9\.\_\-]+$/', $value)) {
            return false;
        }

        // Валидация успешно пройдена.

        return true;
    }

    /**
     * Приводит упоминания и ссылки на пользователя
     * к обычному юзернейму.
     *
     * @param string $value
     *
     * @return string
     */
    public function sanitizeTelegramUsername($value): string
    {
        // Очистим строку от всех вхождений символа "@".
        // @ может быть корректным символом в контексте
        // упоминания, но валидацию он не пройдет.

        $value = str_replace('@', '', $value);

        // Если в качестве юзернейма была передана ссылка
        // на системные домены t.me или telegram.me,
        // извлечём из неё непосредственно юзернейм.

        $url = 'https://'.str_replace(['https://', 'http://'], '', $value);
        $urlInfo = parse_url($url);

        if (!empty($urlInfo['host']) && in_array($urlInfo['host'], ['telegram.me', 't.me']) && !empty($urlInfo['path'])) {
            $value = substr($urlInfo['path'], 1);
        }

        return $value;
    }
}
