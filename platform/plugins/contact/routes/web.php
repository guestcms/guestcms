<?php

use Guestcms\Base\Facades\AdminHelper;
use Guestcms\Contact\Http\Controllers\CustomFieldController;
use Guestcms\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Guestcms\Contact\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'contacts', 'as' => 'contacts.'], function (): void {
            Route::resource('', 'ContactController')->except(['create', 'store'])->parameters(['' => 'contact']);

            Route::group(['prefix' => 'custom-fields', 'as' => 'custom-fields.', 'permission' => 'contact.custom-fields'], function (): void {
                Route::resource('', CustomFieldController::class)->parameters(['' => 'custom-field']);
            });

            Route::post('reply/{contact}', [
                'as' => 'reply',
                'uses' => 'ContactController@postReply',
                'permission' => 'contacts.edit',
            ])->wherePrimaryKey();
        });

        Route::group(['prefix' => 'settings'], function (): void {
            Route::get('contact', [
                'as' => 'contact.settings',
                'uses' => 'Settings\ContactSettingController@edit',
            ]);

            Route::put('contact', [
                'as' => 'contact.settings.update',
                'uses' => 'Settings\ContactSettingController@update',
                'permission' => 'contact.settings',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Theme::registerRoutes(function (): void {
            Route::post('contact/send', [
                'as' => 'public.send.contact',
                'uses' => 'PublicController@postSendContact',
            ]);
        });
    }
});
