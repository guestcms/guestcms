<?php

namespace Guestcms\Gallery\Providers;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Supports\DashboardMenuItem;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Gallery\Facades\Gallery as GalleryFacade;
use Guestcms\Gallery\Models\Gallery;
use Guestcms\Gallery\Models\GalleryMeta;
use Guestcms\Gallery\Repositories\Eloquent\GalleryMetaRepository;
use Guestcms\Gallery\Repositories\Eloquent\GalleryRepository;
use Guestcms\Gallery\Repositories\Interfaces\GalleryInterface;
use Guestcms\Gallery\Repositories\Interfaces\GalleryMetaInterface;
use Guestcms\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Theme\Events\ThemeRoutingBeforeEvent;
use Guestcms\Theme\Facades\SiteMapManager;
use Illuminate\Foundation\AliasLoader;

class GalleryServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(GalleryInterface::class, function () {
            return new GalleryRepository(new Gallery());
        });

        $this->app->bind(GalleryMetaInterface::class, function () {
            return new GalleryMetaRepository(new GalleryMeta());
        });

        AliasLoader::getInstance()->alias('Gallery', GalleryFacade::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/gallery')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['general', 'permissions'])
            ->loadRoutes()
            ->loadAndPublishViews()
            ->loadAndPublishTranslations()
            ->loadMigrations()
            ->publishAssets();

        $this->app->register(EventServiceProvider::class);

        $this->app['events']->listen(ThemeRoutingBeforeEvent::class, function (): void {
            SiteMapManager::registerKey(['galleries']);
        });

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(Gallery::class, fn () => trans('plugins/gallery::gallery.galleries'));
            SlugHelper::setPrefix(Gallery::class, 'galleries', true);
        });

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-gallery')
                        ->priority(5)
                        ->name('plugins/gallery::gallery.menu_name')
                        ->icon('ti ti-camera')
                        ->route('galleries.index')
                );
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Gallery::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(GalleryMeta::class, [
                'images',
            ]);

            LanguageAdvancedManager::addTranslatableMetaBox('gallery_wrap');

            foreach (GalleryFacade::getSupportedModules() as $item) {
                $translatableColumns = array_merge(LanguageAdvancedManager::getTranslatableColumns($item), ['gallery']);

                LanguageAdvancedManager::registerModule($item, $translatableColumns);
            }
        }

        $this->app->booted(function (): void {
            SeoHelper::registerModule([Gallery::class]);

            $this->app->register(HookServiceProvider::class);
        });
    }
}
