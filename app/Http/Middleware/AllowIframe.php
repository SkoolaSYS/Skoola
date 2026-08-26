<?php

namespace App\Http\Middleware;

use Closure;

class AllowIframe
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Allow your PWA to embed the page
        $response->headers->set('X-Frame-Options', 'ALLOWALL');
        $response->headers->set('Content-Security-Policy', "frame-ancestors *");

        return $response;
    }
}
