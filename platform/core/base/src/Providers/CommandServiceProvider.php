<?php

namespace Guestcms\Base\Providers;

use Guestcms\Base\Commands\ActivateLicenseCommand;
use Guestcms\Base\Commands\CleanupSystemCommand;
use Guestcms\Base\Commands\ClearExpiredCacheCommand;
use Guestcms\Base\Commands\ClearLogCommand;
use Guestcms\Base\Commands\CompressImagesCommand;
use Guestcms\Base\Commands\ExportDatabaseCommand;
use Guestcms\Base\Commands\FetchGoogleFontsCommand;
use Guestcms\Base\Commands\GoogleFontsUpdateCommand;
use Guestcms\Base\Commands\ImportDatabaseCommand;
use Guestcms\Base\Commands\InstallCommand;
use Guestcms\Base\Commands\PublishAssetsCommand;
use Guestcms\Base\Commands\UpdateCommand;
use Guestcms\Base\Supports\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\AboutCommand;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            ActivateLicenseCommand::class,
            CleanupSystemCommand::class,
            ClearExpiredCacheCommand::class,
            ClearLogCommand::class,
            ExportDatabaseCommand::class,
            FetchGoogleFontsCommand::class,
            ImportDatabaseCommand::class,
            InstallCommand::class,
            PublishAssetsCommand::class,
            UpdateCommand::class,
            GoogleFontsUpdateCommand::class,
            CompressImagesCommand::class,
        ]);

        AboutCommand::add('Core Information', fn () => [
            'CMS Version' => get_cms_version(),
            'Core Version' => get_core_version(),
        ]);

        $this->app->afterResolving(Schedule::class, function (Schedule $schedule): void {
            $schedule->command(ClearExpiredCacheCommand::class)->everyFiveMinutes();
        });
    }
}
