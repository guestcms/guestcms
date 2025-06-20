<?php

namespace Guestcms\Language\Commands;

use Guestcms\Language\Facades\Language;
use Guestcms\Language\LanguageManager;
use Guestcms\Language\Traits\TranslatedRouteCommandContext;
use Illuminate\Foundation\Console\RouteCacheCommand as BaseRouteCacheCommand;
use Illuminate\Routing\RouteCollection;

class RouteCacheCommand extends BaseRouteCacheCommand
{
    use TranslatedRouteCommandContext;

    public function handle(): int
    {
        $this->call('route:clear');

        foreach (Language::getSupportedLanguagesKeys() as $locale) {
            $path = $this->makeLocaleRoutesPath($locale);

            if ($this->files->exists($path)) {
                $this->files->delete($path);
            }
        }

        $path = $this->laravel->getCachedRoutesPath();

        if ($this->files->exists($path)) {
            $this->files->delete($path);
        }

        $this->cacheRoutesPerLocale();

        $this->components->info('Routes cached successfully for all locales!');

        return self::SUCCESS;
    }

    protected function cacheRoutesPerLocale(): int
    {
        // Store the default routes cache,
        // this way the Application will detect that routes are cached.
        $allLocales = $this->getSupportedLocales();

        $allLocales[] = null;

        $defaultLocale = Language::getDefaultLocale();

        $hideDefaultLocale = Language::hideDefaultLocaleInURL();

        foreach ($allLocales as $locale) {
            if ($hideDefaultLocale && $locale == $defaultLocale) {
                continue;
            }

            $routes = $this->getFreshApplicationRoutesForLocale($locale);

            if ($locale == null && $hideDefaultLocale) {
                $defaultRoutesWithPrefix = $this->getFreshApplicationRoutesForLocale($defaultLocale, true);

                $newRoutes = new RouteCollection();

                foreach ($defaultRoutesWithPrefix as $defaultRoutesWithPrefixItem) {
                    $newRoutes->add($defaultRoutesWithPrefixItem);
                }

                foreach ($routes as $route) {
                    $newRoutes->add($route);
                }

                $routes = $newRoutes;
            }

            if (count($routes) == 0) {
                $this->components->error("Your application doesn't have any routes.");

                return self::FAILURE;
            }

            foreach ($routes as $route) {
                $route->prepareForSerialization();
            }

            $this->files->put(
                $this->makeLocaleRoutesPath($locale),
                $this->buildRouteCacheFile($routes)
            );
        }

        return self::SUCCESS;
    }

    protected function getFreshApplicationRoutesForLocale(?string $locale = null, bool $force = false): RouteCollection
    {
        if (
            $locale === null ||
            (Language::hideDefaultLocaleInURL() && $locale == Language::getDefaultLocale() && ! $force)
        ) {
            return $this->getFreshApplicationRoutes();
        }

        $key = LanguageManager::ENV_ROUTE_KEY;

        if (function_exists('putenv')) {
            putenv("{$key}={$locale}");
        }

        $routes = $this->getFreshApplicationRoutes();

        if (function_exists('putenv')) {
            putenv("{$key}=");
        }

        return $routes;
    }

    protected function buildRouteCacheFile(RouteCollection $routes): string
    {
        $stub = $this->files->get(realpath(__DIR__ . '/../../stubs/routes.stub'));

        return str_replace(
            [
                '{{routes}}',
                '{{translatedRoutes}}',
            ],
            [
                base64_encode(serialize($routes)),
                $this->getLocalization()->getSerializedTranslatedRoutes(),
            ],
            $stub
        );
    }
}
