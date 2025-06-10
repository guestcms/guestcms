<?php

namespace Guestcms\Language\Listeners\Concerns;

use Guestcms\Theme\Theme;

trait EnsureThemePackageExists
{
    public function determineIfThemesExists(): bool
    {
        return class_exists(Theme::class);
    }
}
