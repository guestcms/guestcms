<?php

namespace Guestcms\PluginManagement\Providers;

use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\PluginManagement\Commands\ClearCompiledCommand;
use Guestcms\PluginManagement\Commands\IlluminateClearCompiledCommand as OverrideIlluminateClearCompiledCommand;
use Guestcms\PluginManagement\Commands\PackageDiscoverCommand;
use Guestcms\PluginManagement\Commands\PluginActivateAllCommand;
use Guestcms\PluginManagement\Commands\PluginActivateCommand;
use Guestcms\PluginManagement\Commands\PluginAssetsPublishCommand;
use Guestcms\PluginManagement\Commands\PluginDeactivateAllCommand;
use Guestcms\PluginManagement\Commands\PluginDeactivateCommand;
use Guestcms\PluginManagement\Commands\PluginDiscoverCommand;
use Guestcms\PluginManagement\Commands\PluginInstallFromMarketplaceCommand;
use Guestcms\PluginManagement\Commands\PluginListCommand;
use Guestcms\PluginManagement\Commands\PluginRemoveAllCommand;
use Guestcms\PluginManagement\Commands\PluginRemoveCommand;
use Guestcms\PluginManagement\Commands\PluginUpdateVersionInfoCommand;
use Illuminate\Foundation\Console\ClearCompiledCommand as IlluminateClearCompiledCommand;
use Illuminate\Foundation\Console\PackageDiscoverCommand as IlluminatePackageDiscoverCommand;

class CommandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->extend(IlluminatePackageDiscoverCommand::class, function () {
            return $this->app->make(PackageDiscoverCommand::class);
        });

        $this->app->extend(IlluminateClearCompiledCommand::class, function () {
            return $this->app->make(OverrideIlluminateClearCompiledCommand::class);
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PluginAssetsPublishCommand::class,
                ClearCompiledCommand::class,
                PluginDiscoverCommand::class,
                PluginInstallFromMarketplaceCommand::class,
                PluginActivateCommand::class,
                PluginActivateAllCommand::class,
                PluginDeactivateCommand::class,
                PluginDeactivateAllCommand::class,
                PluginRemoveCommand::class,
                PluginRemoveAllCommand::class,
                PluginListCommand::class,
                PluginUpdateVersionInfoCommand::class,
            ]);
        }
    }
}
