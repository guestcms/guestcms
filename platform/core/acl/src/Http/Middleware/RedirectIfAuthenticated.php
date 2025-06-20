<?php

namespace Guestcms\ACL\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return ! empty($guard)
                    ? redirect('/')
                    : redirect(route('dashboard.index'));
            }
        }

        return $next($request);
    }
}
