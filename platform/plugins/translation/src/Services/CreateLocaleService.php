<?php

namespace Guestcms\Translation\Services;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Theme\Facades\Theme;
use Guestcms\Translation\Manager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class CreateLocaleService
{
    public function handle(string $locale): void
    {
        $manager = app(Manager::class);

        $result = $manager->downloadRemoteLocale($locale);

        $manager->publishLocales();

        if ($result['error']) {
            $defaultLocale = lang_path('en');

            if (File::exists($defaultLocale)) {
                File::copyDirectory($defaultLocale, lang_path($locale));
            }

            $this->createLocaleFiles(lang_path('vendor/core'), $locale);
            $this->createLocaleFiles(lang_path('vendor/packages'), $locale);
            $this->createLocaleFiles(lang_path('vendor/plugins'), $locale);

            $parentTheme = Theme::getThemeName();

            if (Theme::hasInheritTheme()) {
                $parentTheme = Theme::getInheritTheme();
            }

            $themeLocale = Arr::first(BaseHelper::scanFolder(theme_path($parentTheme . '/lang')));

            if ($themeLocale) {
                File::ensureDirectoryExists(lang_path('vendor/themes/' . Theme::getThemeName()));

                File::copy(
                    theme_path($parentTheme . '/lang/' . $themeLocale),
                    lang_path('vendor/themes/' . Theme::getThemeName() . '/' . $locale . '.json')
                );
            }
        }
    }

    protected function createLocaleFiles(string $path, string $locale): void
    {
        $folders = File::directories($path);

        foreach ($folders as $module) {
            foreach (File::directories($module) as $item) {
                if (File::name($item) == 'en') {
                    File::copyDirectory($item, $module . '/' . $locale);
                }
            }
        }
    }
}
