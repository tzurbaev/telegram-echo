<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateTimeHelper;

class DateTimeHelperTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        DateTimeHelper::resetDefaultTimezone();
    }

    /**
     * @dataProvider dateTimeHelperDataProvider
     */
    public function testDateTimeHelperShouldProperlyFormatDates(string $formatType, Carbon $date, string $locale, string $expected)
    {
        // DateTimeHelper должер корректно форматировать даты.

        // Создаем экземпляр DateTimeHelper.
        // Вызываем форматирование переданной даты в нужном формате.
        // Проверяем, что хелпер корректно выполнил форматирование даты.

        $dateTimeHelper = new DateTimeHelper();

        $this->assertSame($expected, $dateTimeHelper->formatLocalized($formatType, $date, $locale));
    }

    public function dateTimeHelperDataProvider()
    {
        return [
            [
                'formatType' => 'date.single',
                'date' => Carbon::createFromFormat('Y-m-d H:i:s', '2017-02-26 13:06:00'),
                'locale' => 'en',
                'expected' => 'Feb 26, 2017',
            ],
            [
                'formatType' => 'date.time',
                'date' => Carbon::createFromFormat('Y-m-d H:i:s', '2017-02-26 13:06:00'),
                'locale' => 'en',
                'expected' => 'Feb 26, 2017 at 13:06:00',
            ],
            [
                'formatType' => 'time.single',
                'date' => Carbon::createFromFormat('Y-m-d H:i:s', '2017-02-26 13:06:00'),
                'locale' => 'en',
                'expected' => '13:06:00',
            ],
        ];
    }
}
