<?php

namespace Guestcms\Installer\Providers;

use Guestcms\Base\Events\FinishedSeederEvent;
use Guestcms\Base\Events\UpdatedEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Installer\Http\Middleware\CheckIfInstalledMiddleware;
use Guestcms\Installer\Http\Middleware\CheckIfInstallingMiddleware;
use Guestcms\Installer\Http\Middleware\RedirectIfNotInstalledMiddleware;
use Carbon\Carbon;
use Illuminate\Routing\Events\RouteMatched;

class InstallerServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this
            ->setNamespace('packages/installer')
            ->loadHelpers()
            ->loadAndPublishConfigurations('installer')
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->publishAssets();

        $this->app['events']->listen(RouteMatched::class, function (): void {
            if (defined('INSTALLED_SESSION_NAME')) {
                $router = $this->app->make('router');

                $router->middlewareGroup('install', [CheckIfInstalledMiddleware::class]);
                $router->middlewareGroup('installing', [CheckIfInstallingMiddleware::class]);

                $router->pushMiddlewareToGroup('web', RedirectIfNotInstalledMiddleware::class);
            }
        });

        $this->app['events']->listen([UpdatedEvent::class, FinishedSeederEvent::class], function (): void {
            BaseHelper::saveFileData(storage_path(INSTALLED_SESSION_NAME), Carbon::now()->toDateTimeString());
        });
    }
}
