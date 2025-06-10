<?php

namespace Guestcms\Backup\Providers;

use Guestcms\Backup\Commands\BackupCleanCommand;
use Guestcms\Backup\Commands\BackupCreateCommand;
use Guestcms\Backup\Commands\BackupListCommand;
use Guestcms\Backup\Commands\BackupRemoveCommand;
use Guestcms\Backup\Commands\BackupRestoreCommand;
use Guestcms\Base\Supports\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            BackupCreateCommand::class,
            BackupRestoreCommand::class,
            BackupRemoveCommand::class,
            BackupListCommand::class,
            BackupCleanCommand::class,
        ]);
    }
}
