<?php

use Guestcms\Base\Facades\AdminHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Guestcms\Testimonial\Http\Controllers'], function (): void {
    AdminHelper::registerRoutes(function (): void {
        Route::group(['prefix' => 'testimonials', 'as' => 'testimonial.'], function (): void {
            Route::resource('', 'TestimonialController')->parameters(['' => 'testimonial']);
        });
    });
});
