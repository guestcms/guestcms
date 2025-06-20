<?php

use Guestcms\Installer\Http\Controllers\AccountController;
use Guestcms\Installer\Http\Controllers\EnvironmentController;
use Guestcms\Installer\Http\Controllers\FinalController;
use Guestcms\Installer\Http\Controllers\InstallController;
use Guestcms\Installer\Http\Controllers\LicenseController;
use Guestcms\Installer\Http\Controllers\RequirementController;
use Guestcms\Installer\Http\Controllers\ThemeController;
use Guestcms\Installer\Http\Controllers\ThemePresetController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'install',
    'as' => 'installers.',
    'middleware' => ['web'],
], function (): void {
    Route::group(['middleware' => 'install'], function (): void {
        Route::get('welcome', [InstallController::class, 'index'])->name('welcome');
        Route::post('welcome/next', [InstallController::class, 'next'])->name('welcome.next');
        Route::resource('requirements', RequirementController::class)->only(['index']);
        Route::resource('environments', EnvironmentController::class)->only(['index', 'store']);
    });

    Route::group(['middleware' => 'installing'], function (): void {
        Route::get('themes', [ThemeController::class, 'index'])->name('themes.index');
        Route::post('themes', [ThemeController::class, 'store'])->name('themes.store');
        Route::get('theme-presets', [ThemePresetController::class, 'index'])->name('theme-presets.index');
        Route::post('theme-presets', [ThemePresetController::class, 'store'])->name('theme-presets.store');
        Route::resource('accounts', AccountController::class)->only(['index', 'store']);
        Route::resource('licenses', LicenseController::class)->only(['index', 'store']);
        Route::get('final', [FinalController::class, 'index'])->name('final');

        Route::post('licenses/skip', [LicenseController::class, 'skip'])->name('licenses.skip');
    });
});
