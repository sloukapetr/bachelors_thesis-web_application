<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\Auth;

use Laravel\Fortify\Fortify;

class CheckTwoFactor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array('two-factor-authentication', config('fortify.features'))) {
            if (Auth::user() AND empty(Auth::user()->two_factor_confirmed_at) AND !$request->route()->named('profile.show')) {
                return redirect()->route('profile.show');
            }
        }
        return $next($request);
    }
}
