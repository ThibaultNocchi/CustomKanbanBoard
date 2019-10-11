<?php

namespace App\Http\Middleware;

use Closure;
use ReflectionClass;

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
                    $type = (new ReflectionClass($original))->getShortName();
                    $type2 = null;
                    if($type === 'Collection') {
                        if($original->count() > 0) $type2 = (new ReflectionClass($original[0]))->getShortName();
                    }
                    $response->setData([
                        'success' => true,
                        'response' => $original,
                        'type' => $type,
                        'inner_type' => $type2
                    ]);
                } else {
                    $response->setData([
                        'success' => true,
                        // 'type' => (new ReflectionClass($original))->getShortName()
                    ]);
                }
            }
        }

        return $response;
    }
}
