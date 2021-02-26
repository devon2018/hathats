<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidApiKeyMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $token = $request->header('access_token');
        if (!$token || $token !== config('auth.api_key')) abort(401, 'A valid api access token must be passed with all requests.');

        return $next($request);
    }
}
