<?php

namespace App\Helpers;

use Carbon\Carbon;
use InvalidArgumentException;

class DateTimeHelper
{
    /**
     * Форматирует дату в соответствии с заданным типом и локалью.
     *
     * Если локаль не задана, будет использовано значение из конфигурации приложения.
     * Для корректной работы на сервере должны быть установлены необходимые
     * системные локали (список доступен в config/locales.php).
     *
     * @param string $type
     * @param \Carbon\Carbon $date
     * @param string $locale = null
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

        return $date->formatLocalized($format);
    }
}
