<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $did_login = Auth::check();

        if (strpos($request->url(), '/auth') === false) {
            if ($did_login) {
                return $next($request);
            }
            return redirect()->route('loginGet');
        }
        else {
            if (!$did_login) {
                return $next($request);
            }
            return redirect()->route('main');
        }
    }
}
