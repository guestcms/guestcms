<?php

namespace Guestcms\Sitemap\Providers;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\PanelSections\PanelSectionItem;
use Guestcms\Base\Services\ClearCacheService;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Setting\PanelSections\SettingCommonPanelSection;
use Guestcms\Sitemap\Sitemap;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;

class SitemapServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    protected bool $defer = true;

    public function register(): void
    {
        $this->app->bind('sitemap', function (Application $app) {
            $config = $app['config']->get('packages.sitemap.config', []);

            return new Sitemap(
                $config,
                $app[Repository::class],
                $app['config'],
                $app['files'],
                $app[ResponseFactory::class],
                $app['view']
            );
        });

        $this->app->alias('sitemap', Sitemap::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('packages/sitemap')
            ->loadAndPublishConfigurations(['config', 'permissions'])
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->publishAssets();

        $this->app['events']->listen([
            CreatedContentEvent::class,
            UpdatedContentEvent::class,
            DeletedContentEvent::class,
        ], function (): void {
            ClearCacheService::make()->clearFrameworkCache();
        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingCommonPanelSection::class,
                function () {
                    return PanelSectionItem::make('sitemap')
                        ->setTitle(trans('packages/sitemap::sitemap.settings.title'))
                        ->withIcon('ti ti-sitemap')
                        ->withDescription(trans('packages/sitemap::sitemap.settings.description'))
                        ->withPriority(1000)
                        ->withRoute('sitemap.settings');
                }
            );
        });
    }

    public function provides(): array
    {
        return ['sitemap', Sitemap::class];
    }
}
