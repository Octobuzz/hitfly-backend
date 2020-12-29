<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->isJson()) {
            return $next($request);
        }

        if (Auth::guard($guard)->check()
            && (
                'social_auth' !== $request->route()->getName()
                && 'social_auth_callback' !== $request->route()->getName()
                && 'graphql.auth.post' !== $request->route()->getName()
                && 'graphql.auth' !== $request->route()->getName()
            )
        ) {
            return redirect('/');
        }

        return $next($request);
    }
}
