<?php

namespace Guestcms\Dashboard\Providers;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Supports\DashboardMenuItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Dashboard\Models\DashboardWidget;
use Guestcms\Dashboard\Models\DashboardWidgetSetting;
use Guestcms\Dashboard\Repositories\Eloquent\DashboardWidgetRepository;
use Guestcms\Dashboard\Repositories\Eloquent\DashboardWidgetSettingRepository;
use Guestcms\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Guestcms\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;

/**
 * @since 02/07/2016 09:50 AM
 */
class DashboardServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(DashboardWidgetInterface::class, function () {
            return new DashboardWidgetRepository(new DashboardWidget());
        });

        $this->app->bind(DashboardWidgetSettingInterface::class, function () {
            return new DashboardWidgetSettingRepository(new DashboardWidgetSetting());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('core/dashboard')
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets()
            ->loadMigrations();

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-core-dashboard')
                        ->priority(-9999)
                        ->name('core/base::layouts.dashboard')
                        ->icon('ti ti-home')
                        ->route('dashboard.index')
                        ->permissions(false)
                );
        });
    }
}
