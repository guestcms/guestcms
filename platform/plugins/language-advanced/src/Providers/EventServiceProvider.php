<?php

namespace Guestcms\LanguageAdvanced\Providers;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\LanguageAdvanced\Listeners\AddDefaultTranslations;
use Guestcms\LanguageAdvanced\Listeners\AddRefLangToAdminBar;
use Guestcms\LanguageAdvanced\Listeners\ClearCacheAfterUpdateData;
use Guestcms\LanguageAdvanced\Listeners\PriorityLanguageAdvancedPluginListener;
use Guestcms\LanguageAdvanced\Listeners\UpdatePermalinkSettingsForEachLanguage;
use Guestcms\PluginManagement\Events\ActivatedPluginEvent;
use Guestcms\Slug\Events\UpdatedPermalinkSettings;
use Guestcms\Theme\Events\RenderingAdminBar;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        CreatedContentEvent::class => [
            AddDefaultTranslations::class,
        ],
        UpdatedContentEvent::class => [
            ClearCacheAfterUpdateData::class,
        ],
        ActivatedPluginEvent::class => [
            PriorityLanguageAdvancedPluginListener::class,
        ],
        UpdatedPermalinkSettings::class => [
            UpdatePermalinkSettingsForEachLanguage::class,
        ],
        RenderingAdminBar::class => [
            AddRefLangToAdminBar::class,
        ],
    ];
}
