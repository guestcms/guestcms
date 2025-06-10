<?php

namespace Guestcms\Menu\Providers;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Supports\DashboardMenuItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Menu\Models\Menu as MenuModel;
use Guestcms\Menu\Models\MenuLocation;
use Guestcms\Menu\Models\MenuNode;
use Guestcms\Menu\Repositories\Eloquent\MenuLocationRepository;
use Guestcms\Menu\Repositories\Eloquent\MenuNodeRepository;
use Guestcms\Menu\Repositories\Eloquent\MenuRepository;
use Guestcms\Menu\Repositories\Interfaces\MenuInterface;
use Guestcms\Menu\Repositories\Interfaces\MenuLocationInterface;
use Guestcms\Menu\Repositories\Interfaces\MenuNodeInterface;
use Guestcms\Theme\Events\RenderingAdminBar;
use Guestcms\Theme\Facades\AdminBar;

class MenuServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(MenuInterface::class, function () {
            return new MenuRepository(new MenuModel());
        });

        $this->app->bind(MenuNodeInterface::class, function () {
            return new MenuNodeRepository(new MenuNode());
        });

        $this->app->bind(MenuLocationInterface::class, function () {
            return new MenuLocationRepository(new MenuLocation());
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('packages/menu')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadHelpers()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-core-menu')
                        ->parentId('cms-core-appearance')
                        ->priority(2)
                        ->name('packages/menu::menu.name')
                        ->icon('ti ti-tournament')
                        ->route('menus.index')
                        ->permissions('menus.index')
                );
        });

        $this->app['events']->listen(RenderingAdminBar::class, function (): void {
            AdminBar::registerLink(
                trans('packages/menu::menu.name'),
                route('menus.index'),
                'appearance',
                'menus.index'
            );
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
    }
}
