<?php

namespace Guestcms\Language\Providers;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Installer\Events\InstallerFinished;
use Guestcms\Language\Listeners\ActivatedPluginListener;
use Guestcms\Language\Listeners\AddHrefLangListener;
use Guestcms\Language\Listeners\CopyThemeOptions;
use Guestcms\Language\Listeners\CopyThemeWidgets;
use Guestcms\Language\Listeners\CreatedContentListener;
use Guestcms\Language\Listeners\CreateSelectedLanguageWhenInstallationFinished;
use Guestcms\Language\Listeners\DeletedContentListener;
use Guestcms\Language\Listeners\ThemeRemoveListener;
use Guestcms\Language\Listeners\UpdatedContentListener;
use Guestcms\PluginManagement\Events\ActivatedPluginEvent;
use Guestcms\Theme\Events\RenderingSingleEvent;
use Guestcms\Theme\Events\ThemeRemoveEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
            CopyThemeOptions::class,
            CopyThemeWidgets::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
        ThemeRemoveEvent::class => [
            ThemeRemoveListener::class,
        ],
        ActivatedPluginEvent::class => [
            ActivatedPluginListener::class,
        ],
        RenderingSingleEvent::class => [
            AddHrefLangListener::class,
        ],
        InstallerFinished::class => [
            CreateSelectedLanguageWhenInstallationFinished::class,
        ],
    ];
}
