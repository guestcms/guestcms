<?php

namespace Guestcms\AuditLog\Providers;

use Guestcms\AuditLog\Commands\ActivityLogClearCommand;
use Guestcms\AuditLog\Commands\CleanOldLogsCommand;
use Guestcms\Base\Supports\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ActivityLogClearCommand::class,
            CleanOldLogsCommand::class,
        ]);
    }
}
