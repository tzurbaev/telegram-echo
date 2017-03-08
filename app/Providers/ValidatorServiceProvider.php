<?php

namespace App\Providers;

use Illuminate\Support\Str;
use App\Validator\CustomRules;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $customRules = app(CustomRules::class);

        $rules = collect(['bot_token', 'chat_identifier']);

        $rules->each(function ($rule) use ($customRules) {
            Validator::extend($rule, function ($attribute, $value, $parameters, $validator) use ($rule, $customRules) {
                $methodName = 'validate'.Str::studly($rule).'Rule';

                return $customRules->{$methodName}($value, $parameters);
            });
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        //
    }
}
