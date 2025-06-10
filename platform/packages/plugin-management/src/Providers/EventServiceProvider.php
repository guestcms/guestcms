<?php

namespace Guestcms\PluginManagement\Providers;

use Guestcms\Base\Events\SeederPrepared;
use Guestcms\Base\Events\SystemUpdateDBMigrated;
use Guestcms\Base\Events\SystemUpdatePublished;
use Guestcms\Base\Listeners\ClearDashboardMenuCaches;
use Guestcms\PluginManagement\Events\ActivatedPluginEvent;
use Guestcms\PluginManagement\Listeners\ActivateAllPlugins;
use Guestcms\PluginManagement\Listeners\ClearPluginCaches;
use Guestcms\PluginManagement\Listeners\CoreUpdatePluginsDB;
use Guestcms\PluginManagement\Listeners\PublishPluginAssets;
use Illuminate\Contracts\Database\Events\MigrationEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MigrationEvent::class => [
            ClearPluginCaches::class,
        ],
        SystemUpdateDBMigrated::class => [
            CoreUpdatePluginsDB::class,
        ],
        SystemUpdatePublished::class => [
            PublishPluginAssets::class,
        ],
        SeederPrepared::class => [
            ActivateAllPlugins::class,
        ],
        ActivatedPluginEvent::class => [
            ClearDashboardMenuCaches::class,
        ],
    ];
}
