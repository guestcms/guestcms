<?php

namespace Guestcms\Language\Http\Middleware;

use Guestcms\Language\Facades\Language;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocalizationRedirectFilter extends LaravelLocalizationMiddlewareBase
{
    public function handle(Request $request, Closure $next)
    {
        // If the URL of the request is in exceptions.
        if ($this->shouldIgnore($request)) {
            return $next($request);
        }

        $currentLocale = Language::getCurrentLocale();
        $defaultLocale = Language::getDefaultLocale();
        $params = explode('/', $request->getPathInfo());
        // Dump the first element (empty string) as getPathInfo() always returns a leading slash
        array_shift($params);

        if (count($params) > 0) {
            $localeCode = $params[0];
            $locales = Language::getSupportedLocales();
            $hideDefaultLocale = Language::hideDefaultLocaleInURL();
            $redirection = false;

            if (! empty($locales[$localeCode])) {
                if ($localeCode === $defaultLocale && $hideDefaultLocale) {
                    $redirection = Language::getNonLocalizedURL();
                }
            } elseif ($currentLocale !== $defaultLocale || ! $hideDefaultLocale) {
                // If the current url does not contain any locale
                // The system redirect the user to the very same url "localized"
                // we use the current locale to redirect him
                if (! Language::getActiveLanguage(['lang_id'])->isEmpty()) {
                    $redirection = Language::getLocalizedURL(Session::get('language'), $request->fullUrl(), [], false);
                }
            }

            if ($redirection) {
                // Save any flashed data for redirect
                Session::reflash();

                return new RedirectResponse($redirection, 302, ['Vary' => 'Accept-Language']);
            }
        }

        return $next($request);
    }
}
