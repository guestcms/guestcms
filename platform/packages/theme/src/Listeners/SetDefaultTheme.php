<?php

namespace Guestcms\Theme\Listeners;

use Guestcms\Setting\Facades\Setting;
use Guestcms\Theme\Facades\Theme;

class SetDefaultTheme
{
    public function handle(): void
    {
        Setting::forceSet('theme', Theme::getThemeName())->set('show_admin_bar', 1)->save();
    }
}
