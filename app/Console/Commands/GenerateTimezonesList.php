<?php

namespace App\Console\Commands;

use IntlTimeZone;
use Illuminate\Console\Command;

class GenerateTimezonesList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:timezones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates timezones list';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!class_exists('\IntlTimeZone')) {
            $this->error('Class "IntlTimeZone" was not found in your current PHP installation.');
            $this->error('Go to http://php.net/intltimezone to get installation info.');

            return;
        }

        $locales = collect(['en', 'ru']);
        $identifiers = collect(timezone_identifiers_list());
        $timezones = collect([]);

        $locales->each(function ($locale) use ($identifiers, $timezones) {
            $timezones->put($locale, collect([]));

            $identifiers->each(function ($tzId) use ($locale, $timezones) {
                $tz = IntlTimeZone::createTimeZone($tzId);

                if ($tzId === 'UTC') {
                    $name = 'UTC';
                } else {
                    $name = (
                        '('.$tz->getDisplayName(false, 6, $locale).') '.
                        $tz->getDisplayName(false, 3, $locale)
                    );
                }

                if ($name === '(GMT) GMT') {
                    $name = explode('/', $tzId)[1];
                }

                $timezones->get($locale)->put($tzId, [
                    'id' => $tzId,
                    'name' => $name,
                    'offset' => $tz->getRawOffset(),
                ]);
            });

            $timezonesList = var_export(
                $timezones->get($locale)->sortBy('offset')->toArray(),
                true
            );

            file_put_contents(
                base_path('resources/lang/'.$locale.'/timezones.php'),
                "<?php\n\n /* Auto-generated file, see GenerateTimezonesList command for more info. */\n\nreturn ".$timezonesList.";\n"
            );
        });

        $this->info('Timezones lists were generated.');
    }
}
