<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Support\Facades\View;

class IsAdmin
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
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
            View::share("is_texon", auth()->user()->email == "texonltd@gmail.com");
            return $next($request);
        } else {
            abort(404);
        }
    }
}
