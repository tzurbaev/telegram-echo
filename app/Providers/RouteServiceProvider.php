<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Exceptions\Api\BotWasNotFoundException;
use App\Exceptions\Api\PostWasNotFoundException;
use App\Exceptions\Api\ChannelWasNotFoundException;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     */
    public function boot()
    {
        parent::boot();

        Route::bind('bot', function ($id) {
            return $this->resolveUserRelationship($id, 'bots', BotWasNotFoundException::class);
        });

        Route::bind('channel', function ($id) {
            return $this->resolveUserRelationship($id, 'channels', ChannelWasNotFoundException::class);
        });

        Route::bind('post', function ($id) {
            return $this->resolveUserRelationship($id, 'posts', PostWasNotFoundException::class);
        });
    }

    protected function resolveUserRelationship($id, string $relation, string $exceptionName)
    {
        $user = Auth::user();

        if (is_null($user)) {
            throw new $exceptionName();
        }

        $relatedItem = $user->{$relation}()->find($id);

        if (is_null($relatedItem)) {
            throw new $exceptionName();
        }

        return $relatedItem;
    }

    /**
     * Define the routes for the application.
     *
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('web')
            ->namespace($this->namespace.'\Api')
            ->group(base_path('routes/api.php'));
    }
}
