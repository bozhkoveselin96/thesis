<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Connected
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!\Session::get('connected_with_classroom')) {
            abort(403);
        }
        return $next($request);
    }
}
