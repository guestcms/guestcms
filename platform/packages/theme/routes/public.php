<?php

use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Theme\Events\ThemeRoutingAfterEvent;
use Guestcms\Theme\Events\ThemeRoutingBeforeEvent;
use Guestcms\Theme\Facades\SiteMapManager;
use Guestcms\Theme\Facades\Theme;
use Guestcms\Theme\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

Theme::registerRoutes(function (): void {
    Route::group(['controller' => PublicController::class], function (): void {
        event(new ThemeRoutingBeforeEvent(app()->make('router')));

        Route::get('/', 'getIndex')->name('public.index');

        if (setting('sitemap_enabled', true)) {
            // Main sitemap index
            Route::get('sitemap.xml', 'getSiteMap')->name('public.sitemap');

            // Handle both standard and paginated sitemaps
            Route::get('{key}.{extension}', 'getSiteMapIndex')
                ->whereIn('extension', SiteMapManager::allowedExtensions())
                ->name('public.sitemap.index');

            Route::get('{slug?}', 'getView')->name('public.single');

            Route::get('{prefix}/{slug?}', 'getViewWithPrefix')
                ->whereIn('prefix', SlugHelper::getAllPrefixes() ?: ['1437bcd2-d94e-4a5fd-9a39-b5d60225e9af']);
        }

        event(new ThemeRoutingAfterEvent(app()->make('router')));
    });
});
