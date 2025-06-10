<?php

namespace Guestcms\Language\Listeners;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Language\Facades\Language;
use Guestcms\Setting\Models\Setting;
use Guestcms\Theme\Events\ThemeRemoveEvent;
use Guestcms\Theme\Facades\ThemeOption;
use Guestcms\Widget\Models\Widget;
use Exception;

class ThemeRemoveListener
{
    public function handle(ThemeRemoveEvent $event): void
    {
        try {
            $languages = Language::getActiveLanguage(['lang_code']);

            foreach ($languages as $language) {
                Widget::query()
                    ->where(['theme' => Widget::getThemeName($language->lang_code, theme: $event->theme)])
                    ->delete();

                Setting::query()
                    ->where(['key', 'LIKE', ThemeOption::getOptionKey('%', $language->lang_code)])
                    ->delete();
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
