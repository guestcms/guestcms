<?php

namespace Guestcms\Page\Providers;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Supports\DashboardMenuItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Page\Models\Page;
use Guestcms\Page\Repositories\Eloquent\PageRepository;
use Guestcms\Page\Repositories\Interfaces\PageInterface;
use Guestcms\Shortcode\View\View;
use Guestcms\Theme\Events\RenderingAdminBar;
use Guestcms\Theme\Facades\AdminBar;
use Illuminate\Support\Facades\View as ViewFacade;

/**
 * @since 02/07/2016 09:50 AM
 */
class PageServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->app->bind(PageInterface::class, function () {
            return new PageRepository(new Page());
        });

        $this
            ->setNamespace('packages/page')
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadHelpers()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadMigrations();

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-core-page')
                        ->priority(2)
                        ->name('packages/page::pages.menu_name')
                        ->icon('ti ti-notebook')
                        ->route('pages.index')
                        ->permissions('pages.index')
                );
        });

        $this->app['events']->listen(RenderingAdminBar::class, function (): void {
            AdminBar::registerLink(
                trans('packages/page::pages.menu_name'),
                route('pages.create'),
                'add-new',
                'pages.create'
            );
        });

        if (function_exists('shortcode')) {
            ViewFacade::composer(['packages/page::themes.page'], function (View $view): void {
                $view->withShortcodes();
            });
        }

        $this->app->booted(function (): void {
            $this->app->register(HookServiceProvider::class);
        });

        $this->app->register(EventServiceProvider::class);
    }
}
