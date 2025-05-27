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
        // $response->headers->set('Retry-After', ($request->header('Retry-After') ?? 60));
       // $response->headers->set('X-RateLimit-Reset', ($request->header('Retry-After') ?? 60) + time());
        // $response->headers->set('X-Ratelimit-Used', ($request->header('X-Ratelimit-Limit') - $request->header('X-RateLimit-Remaining')));
        return $response;
    }
}