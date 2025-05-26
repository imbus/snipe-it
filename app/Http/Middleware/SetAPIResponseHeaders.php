<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetAPIResponseHeaders
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
        $response = $next($request);
        $response->headers->set('Retry-After', ($request->header('Retry-After') ?? 60));
        $response->headers->set('X-RateLimit-Reset', ($request->header('Retry-After') ?? 60));
        $response->headers->set('X-Ratelimit-Remaining', $request->header('X-RateLimit-Remaining'));
        $response->headers->set('X-Ratelimit-Limit', config('app.api_throttle_per_minute'));
        return $response;
    }
}