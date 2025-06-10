<?php

namespace Guestcms\Theme\Providers;

use Guestcms\Base\Events\FormRendering;
use Guestcms\Base\Events\SeederPrepared;
use Guestcms\Base\Events\SystemUpdateDBMigrated;
use Guestcms\Base\Events\SystemUpdatePublished;
use Guestcms\Theme\Listeners\AddFormJsValidation;
use Guestcms\Theme\Listeners\CoreUpdateThemeDB;
use Guestcms\Theme\Listeners\PublishThemeAssets;
use Guestcms\Theme\Listeners\SetDefaultTheme;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SystemUpdateDBMigrated::class => [
            CoreUpdateThemeDB::class,
        ],
        SystemUpdatePublished::class => [
            PublishThemeAssets::class,
        ],
        SeederPrepared::class => [
            SetDefaultTheme::class,
        ],
        FormRendering::class => [
            AddFormJsValidation::class,
        ],
    ];
}
