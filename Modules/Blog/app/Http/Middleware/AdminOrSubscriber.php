<?php

namespace Modules\Blog\app\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOrSubscriber
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->hasRole('admin') || Auth::user()->hasRole('subscriber'))) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
