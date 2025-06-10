<?php

namespace Guestcms\Widget\Providers;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Supports\DashboardMenuItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Theme\Events\RenderingAdminBar;
use Guestcms\Theme\Facades\AdminBar;
use Guestcms\Widget\Facades\WidgetGroup;
use Guestcms\Widget\Factories\WidgetFactory;
use Guestcms\Widget\Models\Widget;
use Guestcms\Widget\Repositories\Eloquent\WidgetRepository;
use Guestcms\Widget\Repositories\Interfaces\WidgetInterface;
use Guestcms\Widget\WidgetGroupCollection;
use Guestcms\Widget\Widgets\CoreSimpleMenu;
use Guestcms\Widget\Widgets\Text;
use Illuminate\Contracts\Foundation\Application;

class WidgetServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(WidgetInterface::class, function () {
            return new WidgetRepository(new Widget());
        });

        $this->app->bind('guestcms.widget', function (Application $app) {
            return new WidgetFactory($app);
        });

        $this->app->singleton('guestcms.widget-group-collection', function (Application $app) {
            return new WidgetGroupCollection($app);
        });
    }

    public function boot(): void
    {
        $this
            ->setNamespace('packages/widget')
            ->loadAndPublishConfigurations(['permissions'])
            ->loadHelpers()
            ->loadRoutes()
            ->loadMigrations()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->publishAssets();

        $this->app->booted(function (): void {
            WidgetGroup::setGroup([
                'id' => 'primary_sidebar',
                'name' => trans('packages/widget::widget.primary_sidebar_name'),
                'description' => trans('packages/widget::widget.primary_sidebar_description'),
            ]);

            register_widget(CoreSimpleMenu::class);
            register_widget(Text::class);
        });

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-core-widget')
                        ->parentId('cms-core-appearance')
                        ->priority(3)
                        ->name('packages/widget::widget.name')
                        ->icon('ti ti-layout')
                        ->route('widgets.index')
                        ->permissions('widgets.index')
                );
        });

        $this->app['events']->listen(RenderingAdminBar::class, function (): void {
            AdminBar::registerLink(
                trans('packages/widget::widget.name'),
                route('widgets.index'),
                'appearance',
                'widgets.index'
            );
        });
    }
}
