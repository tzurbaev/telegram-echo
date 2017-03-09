<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use InvalidArgumentException;

class DateTimeHelper
{
    /**
     * Часовой пояс, используемый по умолчанию.
     *
     * @var string
     */
    protected static $defaultTimezone;

    /**
     * Задает часовой пояс по умолчанию.
     *
     * @param string $timezone
     */
    public static function setDefaultTimezone(string $timezone)
    {
        static::$defaultTimezone = $timezone;
    }

    /**
     * Обнуляет часовой пояс по умолчанию.
     */
    public static function resetDefaultTimezone()
    {
        static::$defaultTimezone = null;
    }

    /**
     * Форматирует дату в соответствии с заданным типом и локалью.
     *
     * Если локаль не задана, будет использовано значение из конфигурации приложения.
     * Для корректной работы на сервере должны быть установлены необходимые
     * системные локали (список доступен в config/locales.php).
     *
     * @param string         $type
     * @param \Carbon\Carbon $date
     * @param string         $locale = null
     *
     * @return string
     */
    public function formatLocalized(string $type, Carbon $date, string $locale = null): string
    {
        $locale = $locale ?? config('app.locale');
        $systemLocale = config('locales.system.'.$locale);

        $format = trans('datetime.'.$type, [], $locale);

        if ($format === 'datetime.'.$type) {
            throw new InvalidArgumentException('Given format type "'.$type.'" was not found for "'.$locale.'" locale.');
        }

        setlocale(LC_TIME, $systemLocale);

        if (!is_null(static::$defaultTimezone) && $date->getTimezone() !== static::$defaultTimezone) {
            $date->setTimezone(static::$defaultTimezone);
        }

        return $date->formatLocalized($format);
    }

    public function extractFromRequest(Request $request, string $field, string $format, string $timezone = null)
    {
        if (!$request->has($field)) {
            return;
        }

        return Carbon::createFromFormat($format, $request->input($field), $timezone)->setTimezone('UTC');
    }
}
