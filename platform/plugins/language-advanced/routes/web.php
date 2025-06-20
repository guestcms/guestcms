<?php

use Guestcms\Base\Facades\AdminHelper;
use Guestcms\LanguageAdvanced\Http\Controllers\LanguageAdvancedController;
use Illuminate\Support\Facades\Route;

AdminHelper::registerRoutes(function (): void {
    Route::group([
        'controller' => LanguageAdvancedController::class,
        'prefix' => 'language-advanced',
    ], function (): void {
        Route::post('save/{id}', [
            'as' => 'language-advanced.save',
            'uses' => 'save',
            'permission' => false,
        ])->wherePrimaryKey();
    });
});
