<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Transformers\BotTransformer;
use Illuminate\Support\Facades\Auth;
use App\Transformers\PostTransformer;
use App\Transformers\UserTransformer;
use App\Transformers\ChannelTransformer;

class ApplicationStateComposer
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $state;

    public function __construct()
    {
        $this->state = collect([]);
    }

    /**
     * Генерирует данные состояния приложения.
     *
     * @param \Illuminate\View\View $view
     */
    public function compose(View $view)
    {
        $this->state
            ->put('application', $this->getApplication());

        if (Auth::check()) {
            $user = Auth::user();

            $userItem = fractal($user, new UserTransformer());
            $channels = fractal($user->channels, new ChannelTransformer());
            $bots = fractal($user->bots, new BotTransformer());
            $posts = fractal($user->posts, new PostTransformer());

            $this->state
                ->put('user', $userItem->toArray())
                ->put('channels', $channels->toArray())
                ->put('bots', $bots->toArray())
                ->put('posts', $posts->toArray());
        }

        $view->with('applicationState', $this->state->toArray());
    }

    protected function getApplication(): array
    {
        return [
            'name' => config('app.name'),
            'env' => config('app.env'),
            'debug' => config('app.debug'),
            'url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'translations' => $this->getTranslations(),
            'routes' => $this->getRoutes()->toArray(),
        ];
    }

    protected function getTranslations(): array
    {
        return [
        ];
    }

    protected function getRoutes()
    {
        $routes = [
            'settings' => ['update' => 'put', 'timezones' => 'get'],
            'channels' => ['index' => 'get', 'store' => 'post'],
            'bots' => ['index' => 'get', 'store' => 'post'],
            'posts' => ['index' => 'get', 'store' => 'post'],
        ];

        return collect($routes)->map(function ($actions, $group) {
            return collect($actions)->map(function ($method, $action) use ($group) {
                return [
                    'name' => 'api.'.$group.'.'.$action,
                    'url' => route('api.'.$group.'.'.$action),
                    'method' => $method,
                ];
            });
        });
    }
}
