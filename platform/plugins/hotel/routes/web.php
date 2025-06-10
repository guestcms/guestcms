<?php

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Hotel\Facades\HotelHelper;
use Guestcms\Hotel\Http\Controllers\CouponController;
use Guestcms\Hotel\Http\Controllers\Front\CouponController as CouponControllerFront;
use Guestcms\Hotel\Http\Controllers\InvoiceController;
use Guestcms\Hotel\Http\Controllers\Settings\CurrencySettingController;
use Guestcms\Hotel\Http\Controllers\Settings\GeneralSettingController;
use Guestcms\Hotel\Http\Controllers\Settings\InvoiceSettingController;
use Guestcms\Hotel\Http\Controllers\Settings\InvoiceTemplateSettingController;
use Guestcms\Hotel\Http\Controllers\Settings\ReviewSettingController;
use Guestcms\Hotel\Models\Food;
use Guestcms\Hotel\Models\Place;
use Guestcms\Hotel\Models\Room;
use Guestcms\Hotel\Models\RoomCategory;
use Guestcms\Hotel\Models\Service;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Guestcms\Hotel\Http\Controllers', 'middleware' => ['web', 'core']], function (): void {
    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/hotel', 'middleware' => 'auth'], function (): void {
        Route::group(['prefix' => 'settings', 'as' => 'hotel.settings.', 'permission' => 'hotel.settings'], function (): void {
            Route::get('general', [GeneralSettingController::class, 'edit'])->name('general');
            Route::put('general', [GeneralSettingController::class, 'update'])->name('general.update');

            Route::get('review', [ReviewSettingController::class, 'edit'])->name('review');
            Route::put('review', [ReviewSettingController::class, 'update'])->name('review.update');

            Route::get('currencies', [CurrencySettingController::class, 'edit'])->name('currencies');
            Route::put('currencies', [CurrencySettingController::class, 'update'])->name('currencies.update');

            Route::get('invoice', [InvoiceSettingController::class, 'edit'])->name('invoice');
            Route::put('invoice', [InvoiceSettingController::class, 'update'])->name('invoice.update');

            Route::get('invoice-template', [InvoiceTemplateSettingController::class, 'edit'])->name('invoice-template');
            Route::put('invoice-template', [InvoiceTemplateSettingController::class, 'update'])->name('invoice-template.update');

            Route::post('invoice-template/reset', [
                'as' => 'invoice-template.reset',
                'uses' => 'Settings\InvoiceTemplateSettingController@reset',
                'permission' => 'invoice.template',
                'middleware' => 'preventDemo',
            ]);

            Route::get('invoice-template/preview', [
                'as' => 'invoice-template.preview',
                'uses' => 'Settings\InvoiceTemplateSettingController@preview',
                'permission' => 'invoice.template',
            ]);
        });

        Route::group(['prefix' => 'rooms', 'as' => 'room.'], function (): void {
            Route::resource('', 'RoomController')->parameters(['' => 'room']);

            Route::get('room-availability/{room}', [
                'as' => 'availability',
                'uses' => 'RoomController@getRoomAvailability',
                'permission' => 'room.edit',
            ])->wherePrimaryKey();

            Route::post('room-availability/{id}', [
                'as' => 'availability.post',
                'uses' => 'RoomController@storeRoomAvailability',
                'permission' => 'room.edit',
            ])->wherePrimaryKey();

            Route::post('update-order-by', [
                'as' => 'update-order-by',
                'uses' => 'RoomController@postUpdateOrderby',
                'permission' => 'room.edit',
            ]);
        });

        Route::group(['prefix' => 'amenities', 'as' => 'amenity.'], function (): void {
            Route::resource('', 'AmenityController')->parameters(['' => 'amenity']);
        });

        Route::group(['prefix' => 'foods', 'as' => 'food.'], function (): void {
            Route::resource('', 'FoodController')->parameters(['' => 'food']);
        });

        Route::group(['prefix' => 'food-types', 'as' => 'food-type.'], function (): void {
            Route::resource('', 'FoodTypeController')->parameters(['' => 'food-type']);
        });

        Route::group(['prefix' => 'bookings', 'as' => 'booking.'], function (): void {
            Route::resource('', 'BookingController')->parameters(['' => 'booking'])->except(['create', 'store']);
        });

        Route::get('/booking-reports', [
            'uses' => 'BookingReportController@index',
            'as' => 'booking.reports.index',
        ]);

        Route::get('/booking-calendar', [
            'uses' => 'BookingCalendarController@index',
            'as' => 'booking.calendar.index',
        ]);

        Route::get('/booking-reports/bookings', [
            'uses' => 'BookingReportController@index',
            'as' => 'booking.reports.index',
        ]);

        Route::post('recent-bookings', [
            'as' => 'booking.reports.recent-bookings',
            'uses' => 'BookingReportController@getRecentBookings',
            'permission' => 'booking.reports.index',
        ]);

        Route::get('/booking-reports/records', [
            'uses' => 'BookingReportRecordController@index',
            'as' => 'booking.reports.records.index',
            'permission' => 'booking.reports.index',
        ]);

        Route::group(['prefix' => 'customers', 'as' => 'customer.'], function (): void {
            Route::resource('', 'CustomerController')->parameters(['' => 'customer']);
        });

        Route::group(['prefix' => 'room-categories', 'as' => 'room-category.'], function (): void {
            Route::resource('', 'RoomCategoryController')->parameters(['' => 'room-category']);
        });

        Route::group(['prefix' => 'features', 'as' => 'feature.'], function (): void {
            Route::resource('', 'FeatureController')->parameters(['' => 'feature']);
        });

        Route::group(['prefix' => 'services', 'as' => 'service.'], function (): void {
            Route::resource('', 'ServiceController')->parameters(['' => 'service']);
        });

        Route::group(['prefix' => 'places', 'as' => 'place.'], function (): void {
            Route::resource('', 'PlaceController')->parameters(['' => 'place']);
        });

        Route::group(['prefix' => 'taxes', 'as' => 'tax.'], function (): void {
            Route::resource('', 'TaxController')->parameters(['' => 'tax']);
        });

        Route::group(['prefix' => 'invoices', 'as' => 'invoices.'], function (): void {
            Route::resource('', 'InvoiceController')->parameters(['' => 'invoice']);
            Route::get('{invoice}', [InvoiceController::class, 'show'])
                ->name('show')
                ->permission('invoices.edit')
                ->wherePrimaryKey();
            Route::get('{invoice}/generate-invoice', 'InvoiceController@getGenerateInvoice')
                ->name('generate')
                ->permission('invoices.edit')
                ->wherePrimaryKey();
        });

        Route::group(['prefix' => 'reviews', 'as' => 'review.'], function (): void {
            Route::resource('', 'ReviewController')->parameters(['' => 'review'])->only(['index', 'destroy']);
        });

        Route::group(['prefix' => 'coupons', 'as' => 'coupons.'], function (): void {
            Route::resource('', CouponController::class)
                ->parameters(['' => 'coupon']);

            Route::post('generate-coupon', [
                'as' => 'generate-coupon',
                'uses' => 'CouponController@generateCouponCode',
                'permission' => 'coupons.index',
            ]);

            Route::delete('deletes', [
                'as' => 'deletes',
                'uses' => 'CouponController@deletes',
                'permission' => 'coupons.destroy',
            ]);
        });
    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Theme::registerRoutes(function (): void {
            Route::get(SlugHelper::getPrefix(Room::class, 'rooms'), 'PublicController@getRooms')->name('public.rooms');

            Route::get(SlugHelper::getPrefix(Room::class, 'rooms') . '/{slug}', 'PublicController@getRoom');

            Route::get(SlugHelper::getPrefix(RoomCategory::class, 'room-categories') . '/{slug}', 'PublicController@getRoomCategory');

            Route::get(SlugHelper::getPrefix(Service::class, 'services') . '/{slug}', 'PublicController@getService');
            Route::get(SlugHelper::getPrefix(Food::class, 'foods') . '/{slug}', 'PublicController@getFood');

            Route::get(SlugHelper::getPrefix(Place::class, 'places') . '/{slug}', 'PublicController@getPlace');

            Route::post('booking', 'PublicController@postBooking')->name('public.booking');
            Route::get('booking/{token}', 'PublicController@getBooking')->name('public.booking.form');

            Route::post('checkout', 'PublicController@postCheckout')->name('public.booking.checkout');

            Route::get('checkout/{transactionId}', 'PublicController@checkoutSuccess')
                ->name('public.booking.information');

            Route::prefix('coupon')->name('coupon.')->group(function (): void {
                Route::post('apply', [CouponControllerFront::class, 'apply'])->name('apply');
                Route::post('remove', [CouponControllerFront::class, 'remove'])->name('remove');
                Route::get('refresh', [CouponControllerFront::class, 'refresh'])->name('refresh');
            });

            Route::get('ajax/calculate-amount', 'PublicController@ajaxCalculateBookingAmount')
                ->name('public.booking.ajax.calculate-amount');

            Route::get('currency/switch/{code?}', [
                'as' => 'public.change-currency',
                'uses' => 'PublicController@changeCurrency',
            ]);
        });
    }
});

if (defined('THEME_MODULE_SCREEN_NAME')) {
    Theme::registerRoutes(function (): void {
        Route::group([
            'namespace' => 'Guestcms\Hotel\Http\Controllers\Front\Customers',
            'middleware' => ['web', 'core', 'customer.guest'],
            'as' => 'customer.',
        ], function (): void {
            Route::get('login', 'LoginController@showLoginForm')->name('login');
            Route::post('login', 'LoginController@login')->name('login.post');

            Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'RegisterController@register')->name('register.post');

            Route::get(
                'password/request',
                'ForgotPasswordController@showLinkRequestForm'
            )->name('password.request');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset.update');
            Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
        });

        Route::group([
            'namespace' => 'Guestcms\Hotel\Http\Controllers\Front\Customers',
            'middleware' => [
                'web',
                'core',
                HotelHelper::isEnableEmailVerification() ? 'customer' : 'customer.guest',
            ],
            'as' => 'customer.',
        ], function (): void {
            Route::get('register/confirm/resend', 'RegisterController@resendConfirmation')
                ->name('resend_confirmation');
            Route::get('register/confirm/{user}', 'RegisterController@confirm')
                ->name('confirm');
        });

        Route::group([
            'namespace' => 'Guestcms\Hotel\Http\Controllers\Front\Customers',
            'middleware' => ['web', 'core', 'customer'],
            'prefix' => 'customer',
            'as' => 'customer.',
        ], function (): void {
            Route::get('logout', 'LoginController@logout')->name('logout');

            Route::get('overview', [
                'as' => 'overview',
                'uses' => 'PublicController@getOverview',
            ]);

            Route::get('edit-account', [
                'as' => 'edit-account',
                'uses' => 'PublicController@getEditAccount',
            ]);

            Route::post('edit-account', [
                'as' => 'edit-account.post',
                'uses' => 'PublicController@postEditAccount',
            ]);

            Route::get('change-password', [
                'as' => 'change-password',
                'uses' => 'PublicController@getChangePassword',
            ]);

            Route::post('change-password', [
                'as' => 'post.change-password',
                'uses' => 'PublicController@postChangePassword',
            ]);

            Route::post('avatar', [
                'as' => 'avatar',
                'uses' => 'PublicController@postAvatar',
            ]);

            Route::get('bookings', [
                'as' => 'bookings',
                'uses' => 'BookingController@index',
            ]);

            Route::get('bookings/{id}', [
                'as' => 'bookings.show',
                'uses' => 'BookingController@show',
            ]);

            Route::get('generate-invoice/{invoice}', [
                'as' => 'generate-invoice',
                'uses' => 'BookingController@getGenerateInvoice',
            ])->wherePrimaryKey();

            if (HotelHelper::isReviewEnabled()) {
                Route::get('reviews', [
                    'as' => 'reviews',
                    'uses' => 'ReviewController@index',
                ]);
            }
        });
        if (HotelHelper::isReviewEnabled()) {
            Route::group([
                'namespace' => 'Guestcms\Hotel\Http\Controllers\Front',
                'middleware' => ['web', 'core'],
                'prefix' => 'customer',
                'as' => 'customer.',
            ], function (): void {
                Route::get('ajax/review/{key}', [
                    'as' => 'ajax.review.index',
                    'uses' => 'ReviewController@index',
                ]);
                Route::post('ajax/review/{key}', [
                    'as' => 'ajax.review.store',
                    'uses' => 'ReviewController@store',
                ]);
            });
        }
    });
}
