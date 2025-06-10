<?php

use Guestcms\Language\Http\Controllers\API\LanguageController;
use Guestcms\Language\Http\Middleware\ApiLanguageMiddleware;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api', ApiLanguageMiddleware::class],
    'prefix' => 'api/v1/languages',
    'namespace' => 'Guestcms\Language\Http\Controllers\API',
], function (): void {
    Route::get('/', [LanguageController::class, 'index']);
    Route::get('/current', [LanguageController::class, 'getCurrentLanguage']);
});
