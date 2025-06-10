<?php

namespace Guestcms\Slug\Providers;

use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Slug\Commands\ChangeSlugPrefixCommand;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ChangeSlugPrefixCommand::class,
        ]);
    }
}
