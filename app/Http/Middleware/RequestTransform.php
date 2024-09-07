<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;

class RequestTransform
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $inputs = $request->all();
        foreach ($inputs as $key => $value) {
            unset($request[$key]);
            $request->merge([Str::snake($key) => $value]);
        }
        return $next($request);
    }
}

