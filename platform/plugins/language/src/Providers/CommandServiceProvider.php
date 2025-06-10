<?php

namespace Guestcms\Language\Providers;

use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Language\Commands\RouteCacheCommand;
use Guestcms\Language\Commands\RouteClearCommand;
use Guestcms\Language\Commands\RouteTranslationsListCommand;
use Illuminate\Foundation\Console\RouteCacheCommand as BaseRouteCacheCommand;
use Illuminate\Foundation\Console\RouteClearCommand as BaseRouteClearCommand;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            RouteTranslationsListCommand::class,
        ]);

        $this->app->extend(BaseRouteCacheCommand::class, function () {
            return new RouteCacheCommand($this->app['files']);
        });

        $this->app->extend(BaseRouteClearCommand::class, function () {
            return new RouteClearCommand($this->app['files']);
        });
    }
}
