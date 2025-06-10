<?php

use Guestcms\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Guestcms\Captcha\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'settings/captcha', 'as' => 'captcha.settings', 'permission' => 'captcha.settings'], function (): void {
            Route::get('/', [
                'uses' => 'Settings\CaptchaSettingController@edit',
            ]);

            Route::put('/', [
                'as' => '.update',
                'uses' => 'Settings\CaptchaSettingController@update',
            ]);
        });
    });
});
