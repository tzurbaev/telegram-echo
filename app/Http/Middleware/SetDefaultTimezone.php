<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\DateTimeHelper;

class SetDefaultTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!is_null($request->user())) {
            DateTimeHelper::setDefaultTimezone($request->user()->timezone);
        }

        return $next($request);
    }
}
