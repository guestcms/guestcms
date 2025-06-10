<?php

namespace Guestcms\Menu\Providers;

use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Menu\Commands\ClearMenuCacheCommand;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ClearMenuCacheCommand::class,
        ]);
    }
}
