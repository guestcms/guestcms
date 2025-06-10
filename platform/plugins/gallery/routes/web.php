<?php

use Guestcms\Base\Facades\AdminHelper;
use Guestcms\Gallery\Models\Gallery;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Guestcms\Gallery\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'galleries', 'as' => 'galleries.'], function (): void {
            Route::resource('', 'GalleryController')->parameters(['' => 'gallery']);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Theme::registerRoutes(function (): void {
            if (! theme_option('galleries_page_id')) {
                $prefix = SlugHelper::getPrefix(Gallery::class, 'galleries');

                Route::get($prefix ?: 'galleries', [
                    'as' => 'public.galleries',
                    'uses' => 'PublicController@getGalleries',
                ]);
            }
        });
    }
});
