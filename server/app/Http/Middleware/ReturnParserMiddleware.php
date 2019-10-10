<?php

namespace App\Http\Middleware;

use Closure;

class ReturnParserMiddleware
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

        $response = $next($request);

        // Post-Middleware Action
        if (get_class($response) == 'Illuminate\Http\JsonResponse') {
            $original = $response->getOriginalContent();
            if (!(isset($original['success']) && !$original['success'])) {
                if ($original) {
                    $response->setData([
                        'success' => true,
                        'response' => $original
                    ]);
                } else {
                    $response->setData([
                        'success' => true
                    ]);
                }
            }
        }

        return $response;
    }
}
