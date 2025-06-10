<?php

namespace Guestcms\Base\Http\Middleware;

use Guestcms\Base\Exceptions\DisabledInDemoModeException;
use Guestcms\Base\Facades\BaseHelper;
use Closure;
use Illuminate\Http\Request;

class DisableInDemoModeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (BaseHelper::hasDemoModeEnabled()) {
            throw new DisabledInDemoModeException();
        }

        return $next($request);
    }
}
