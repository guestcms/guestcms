<?php

namespace Guestcms\Language\Traits;

use Guestcms\Language\Facades\Language;
use Guestcms\Language\LanguageManager;

trait TranslatedRouteCommandContext
{
    protected function isSupportedLocale(?string $locale): bool
    {
        return in_array($locale, $this->getSupportedLocales());
    }

    protected function getSupportedLocales(): array
    {
        return $this->getLocalization()->getSupportedLanguagesKeys();
    }

    protected function getLocalization()
    {
        return app(LanguageManager::class);
    }

    protected function getBootstrapPath(): string
    {
        return $this->laravel->bootstrapPath();
    }

    protected function makeLocaleRoutesPath(?string $locale = ''): string
    {
        $path = $this->laravel->getCachedRoutesPath();

        if (! $locale || (Language::hideDefaultLocaleInURL() && $locale == Language::getDefaultLocale())) {
            return $path;
        }

        return substr($path, 0, -4) . '_' . $locale . '.php';
    }
}
