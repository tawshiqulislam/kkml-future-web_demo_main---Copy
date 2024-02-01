<?php

namespace App\Http\Middleware;

use Closure;

class TexonAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->email !== "texonltd@gmail.com") {
            abort(404);
        }
        return $next($request);
    }
}
