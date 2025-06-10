<?php

use Guestcms\Base\Http\Middleware\RequiresJsonRequestMiddleware;
use Guestcms\Shortcode\Http\Controllers\ShortcodeController;
use Guestcms\Theme\Facades\Theme;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Theme::registerRoutes(function (): void {
    Route::post('ajax/render-ui-blocks', [ShortcodeController::class, 'ajaxRenderUiBlock'])
        ->name('public.ajax.render-ui-block')
        ->middleware(RequiresJsonRequestMiddleware::class)
        ->withoutMiddleware(VerifyCsrfToken::class);
});
