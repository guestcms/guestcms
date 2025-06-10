<?php

namespace Guestcms\Language\Http\Middleware;

use Guestcms\Language\Facades\Language;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ApiLanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('language')) {
            $languageCode = $request->query('language');

            if (Language::checkLocaleInSupportedLocales($languageCode)) {
                App::setLocale($languageCode);
                Language::setLocale($languageCode);
            }
        }

        return $next($request);
    }
}
