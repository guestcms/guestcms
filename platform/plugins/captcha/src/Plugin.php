<?php

namespace Guestcms\Captcha;

use Guestcms\PluginManagement\Abstracts\PluginOperationAbstract;
use Guestcms\Setting\Facades\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::delete([
            'enable_captcha',
            'captcha_type',
            'captcha_hide_badge',
            'captcha_site_key',
            'captcha_secret',
        ]);
    }
}
