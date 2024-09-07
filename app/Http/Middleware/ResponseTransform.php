<?php

namespace App\Http\Middleware;

use App\Helpers\KeyCaseConverter;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResponseTransform
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);
        if ($response instanceof JsonResponse) {
            $response->setData(
                resolve(KeyCaseConverter::class)->convert(
                    KeyCaseConverter::CASE_CAMEL,
                    json_decode($response->content(), true)
                )
            );
        }
        return $response;
    }
}

