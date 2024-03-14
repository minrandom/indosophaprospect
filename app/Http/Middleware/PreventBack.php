<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PreventBack
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
  
    public function handle(Request $request, Closure $next)
    {
        return $response= $next($request);

        // Set headers to prevent caching
        $response->header("Cache-Control", "no-cache, no-store, must-revalidate");
$response->header("Pragma", "no-cache");
$response->header("Expires", "0");

        return $response;
    }
}
