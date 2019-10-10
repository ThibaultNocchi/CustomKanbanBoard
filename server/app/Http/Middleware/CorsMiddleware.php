<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pre-Middleware Action

        if ($request->getMethod() === "OPTIONS") {
            return response('')->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE');
        }

        $response = $next($request);

        // Post-Middleware Action
        if(get_class($response) != 'Symfony\Component\HttpFoundation\StreamedResponse'){
            $response->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE');
        }

        return $response;
    }
}
