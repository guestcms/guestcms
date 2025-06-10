<?php

use Guestcms\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Guestcms\Page\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'pages', 'as' => 'pages.'], function (): void {
            Route::resource('', 'PageController')->parameters(['' => 'page']);
        });
    });
});
