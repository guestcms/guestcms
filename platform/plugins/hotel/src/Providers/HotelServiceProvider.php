<?php

namespace Guestcms\Hotel\Providers;

use Guestcms\Base\Facades\DashboardMenu;
use Guestcms\Base\Facades\EmailHandler;
use Guestcms\Base\Facades\PanelSectionManager;
use Guestcms\Base\Supports\DashboardMenu as DashboardMenuSupport;
use Guestcms\Base\Traits\LoadAndPublishDataTrait;
use Guestcms\Captcha\Facades\Captcha;
use Guestcms\Hotel\Facades\HotelHelper;
use Guestcms\Hotel\Forms\Fronts\Auth\ForgotPasswordForm;
use Guestcms\Hotel\Forms\Fronts\Auth\LoginForm;
use Guestcms\Hotel\Forms\Fronts\Auth\RegisterForm;
use Guestcms\Hotel\Forms\Fronts\Auth\ResetPasswordForm;
use Guestcms\Hotel\Http\Middleware\RedirectIfCustomer;
use Guestcms\Hotel\Http\Middleware\RedirectIfNotCustomer;
use Guestcms\Hotel\Http\Requests\Fronts\Auth\ForgotPasswordRequest;
use Guestcms\Hotel\Http\Requests\Fronts\Auth\LoginRequest;
use Guestcms\Hotel\Http\Requests\Fronts\Auth\RegisterRequest;
use Guestcms\Hotel\Http\Requests\Fronts\Auth\ResetPasswordRequest;
use Guestcms\Hotel\Models\Amenity;
use Guestcms\Hotel\Models\Booking;
use Guestcms\Hotel\Models\BookingAddress;
use Guestcms\Hotel\Models\BookingRoom;
use Guestcms\Hotel\Models\Currency;
use Guestcms\Hotel\Models\Customer;
use Guestcms\Hotel\Models\Feature;
use Guestcms\Hotel\Models\Food;
use Guestcms\Hotel\Models\FoodType;
use Guestcms\Hotel\Models\Place;
use Guestcms\Hotel\Models\Room;
use Guestcms\Hotel\Models\RoomCategory;
use Guestcms\Hotel\Models\RoomDate;
use Guestcms\Hotel\Models\Service;
use Guestcms\Hotel\Models\Tax;
use Guestcms\Hotel\PanelSections\SettingHotelPanelSection;
use Guestcms\Hotel\Repositories\Eloquent\AmenityRepository;
use Guestcms\Hotel\Repositories\Eloquent\BookingAddressRepository;
use Guestcms\Hotel\Repositories\Eloquent\BookingRepository;
use Guestcms\Hotel\Repositories\Eloquent\BookingRoomRepository;
use Guestcms\Hotel\Repositories\Eloquent\CurrencyRepository;
use Guestcms\Hotel\Repositories\Eloquent\CustomerRepository;
use Guestcms\Hotel\Repositories\Eloquent\FeatureRepository;
use Guestcms\Hotel\Repositories\Eloquent\FoodRepository;
use Guestcms\Hotel\Repositories\Eloquent\FoodTypeRepository;
use Guestcms\Hotel\Repositories\Eloquent\PlaceRepository;
use Guestcms\Hotel\Repositories\Eloquent\RoomCategoryRepository;
use Guestcms\Hotel\Repositories\Eloquent\RoomDateRepository;
use Guestcms\Hotel\Repositories\Eloquent\RoomRepository;
use Guestcms\Hotel\Repositories\Eloquent\ServiceRepository;
use Guestcms\Hotel\Repositories\Eloquent\TaxRepository;
use Guestcms\Hotel\Repositories\Interfaces\AmenityInterface;
use Guestcms\Hotel\Repositories\Interfaces\BookingAddressInterface;
use Guestcms\Hotel\Repositories\Interfaces\BookingInterface;
use Guestcms\Hotel\Repositories\Interfaces\BookingRoomInterface;
use Guestcms\Hotel\Repositories\Interfaces\CurrencyInterface;
use Guestcms\Hotel\Repositories\Interfaces\CustomerInterface;
use Guestcms\Hotel\Repositories\Interfaces\FeatureInterface;
use Guestcms\Hotel\Repositories\Interfaces\FoodInterface;
use Guestcms\Hotel\Repositories\Interfaces\FoodTypeInterface;
use Guestcms\Hotel\Repositories\Interfaces\PlaceInterface;
use Guestcms\Hotel\Repositories\Interfaces\RoomCategoryInterface;
use Guestcms\Hotel\Repositories\Interfaces\RoomDateInterface;
use Guestcms\Hotel\Repositories\Interfaces\RoomInterface;
use Guestcms\Hotel\Repositories\Interfaces\ServiceInterface;
use Guestcms\Hotel\Repositories\Interfaces\TaxInterface;
use Guestcms\LanguageAdvanced\Supports\LanguageAdvancedManager;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\SocialLogin\Facades\SocialService;
use Guestcms\Theme\Facades\SiteMapManager;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HotelServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        config([
            'auth.guards.customer' => [
                'driver' => 'session',
                'provider' => 'customers',
            ],
            'auth.providers.customers' => [
                'driver' => 'eloquent',
                'model' => Customer::class,
            ],
            'auth.passwords.customers' => [
                'provider' => 'customers',
                'table' => 'ht_customer_password_resets',
                'expire' => 60,
            ],
        ]);
        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
        $router->aliasMiddleware('customer.guest', RedirectIfCustomer::class);

        $aliasLoader = AliasLoader::getInstance();

        if (! class_exists('HotelHelper')) {
            $aliasLoader->alias('HotelHelper', HotelHelper::class);
        }

        $this->app->bind(CurrencyInterface::class, function () {
            return new CurrencyRepository(new Currency());
        });

        $this->app->bind(RoomInterface::class, function () {
            return new RoomRepository(new Room());
        });

        $this->app->bind(RoomDateInterface::class, function () {
            return new RoomDateRepository(new RoomDate());
        });

        $this->app->bind(AmenityInterface::class, function () {
            return new AmenityRepository(new Amenity());
        });

        $this->app->bind(FoodInterface::class, function () {
            return new FoodRepository(new Food());
        });

        $this->app->bind(FoodTypeInterface::class, function () {
            return new FoodTypeRepository(new FoodType());
        });

        $this->app->bind(BookingInterface::class, function () {
            return new BookingRepository(new Booking());
        });

        $this->app->bind(BookingAddressInterface::class, function () {
            return new BookingAddressRepository(new BookingAddress());
        });

        $this->app->bind(BookingRoomInterface::class, function () {
            return new BookingRoomRepository(new BookingRoom());
        });

        $this->app->bind(CustomerInterface::class, function () {
            return new CustomerRepository(new Customer());
        });

        $this->app->bind(RoomCategoryInterface::class, function () {
            return new RoomCategoryRepository(new RoomCategory());
        });

        $this->app->bind(FeatureInterface::class, function () {
            return new FeatureRepository(new Feature());
        });

        $this->app->bind(ServiceInterface::class, function () {
            return new ServiceRepository(new Service());
        });

        $this->app->bind(PlaceInterface::class, function () {
            return new PlaceRepository(new Place());
        });

        $this->app->bind(TaxInterface::class, function () {
            return new TaxRepository(new Tax());
        });
    }

    public function boot(): void
    {
        $this->setNamespace('plugins/hotel')
            ->loadAndPublishConfigurations(['permissions', 'hotel', 'email'])
            ->loadMigrations()
            ->loadHelpers()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes()
            ->publishAssets();

        SlugHelper::registering(function (): void {
            SlugHelper::registerModule(Room::class, fn () => trans('plugins/hotel::room.rooms'));
            SlugHelper::setPrefix(Room::class, 'rooms');

            SlugHelper::registerModule(RoomCategory::class, fn () => trans('plugins/hotel::room.categories'));
            SlugHelper::setPrefix(RoomCategory::class, 'room-categories');

            SlugHelper::registerModule(Place::class, fn () => trans('plugins/hotel::place.places'));
            SlugHelper::setPrefix(Place::class, 'places');

            SlugHelper::registerModule(Service::class, fn () => trans('plugins/hotel::service.services'));
            SlugHelper::setPrefix(Service::class, 'services');

            SlugHelper::registerModule(Food::class, fn () => trans('plugins/hotel::food.name'));
            SlugHelper::setPrefix(Food::class, 'foods');
        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::register(SettingHotelPanelSection::class);
        });

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'cms-plugins-hotel',
                    'priority' => 1,
                    'name' => 'plugins/hotel::hotel.name',
                    'icon' => 'ti ti-building-skyscraper',
                    'route' => 'room.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-room',
                    'priority' => 0,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::room.name',
                    'route' => 'room.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-room-category',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::room-category.name',
                    'route' => 'room-category.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-amenities',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::amenity.name',
                    'icon' => null,
                    'route' => 'amenity.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-food',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::food.name',
                    'route' => 'food.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-food-type',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::food-type.name',
                    'route' => 'food-type.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-feature',
                    'priority' => 5,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::feature.menu',
                    'route' => 'feature.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-service',
                    'priority' => 6,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::service.name',
                    'route' => 'service.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-place',
                    'priority' => 7,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::place.name',
                    'route' => 'place.index',
                ])
                ->when(
                    HotelHelper::isReviewEnabled(),
                    function (DashboardMenuSupport $menu) {
                        return $menu->registerItem([
                            'id' => 'cms-plugins-hotel-review',
                            'priority' => 8,
                            'parent_id' => 'cms-plugins-hotel',
                            'name' => 'plugins/hotel::review.name',
                            'route' => 'review.index',
                        ]);
                    }
                )
                ->registerItem([
                    'id' => 'cms-plugins-customer',
                    'priority' => 9,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::customer.name',
                    'route' => 'customer.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-tax',
                    'priority' => 10,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::tax.name',
                    'route' => 'tax.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-hotel-coupons',
                    'priority' => 11,
                    'parent_id' => 'cms-plugins-hotel',
                    'name' => 'plugins/hotel::coupon.name',
                    'route' => 'coupons.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-booking',
                    'priority' => 1,
                    'name' => 'plugins/hotel::booking.name',
                    'icon' => 'ti ti-calendar-event',
                    'route' => 'booking.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-booking-reports',
                    'priority' => 1,
                    'parent_id' => 'cms-plugins-booking',
                    'name' => 'plugins/hotel::booking.reports',
                    'route' => 'booking.reports.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-booking-calendar',
                    'priority' => 2,
                    'parent_id' => 'cms-plugins-booking',
                    'name' => 'plugins/hotel::booking.calendar',
                    'route' => 'booking.calendar.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-booking-list',
                    'priority' => 3,
                    'parent_id' => 'cms-plugins-booking',
                    'name' => 'plugins/hotel::booking.name',
                    'route' => 'booking.index',
                ])
                ->registerItem([
                    'id' => 'cms-plugins-invoice',
                    'priority' => 4,
                    'parent_id' => 'cms-plugins-booking',
                    'name' => 'plugins/hotel::invoice.name',
                    'route' => 'invoices.index',
                ]);
        });

        $this->app['events']->listen(RouteMatched::class, function (): void {
            EmailHandler::addTemplateSettings(HOTEL_MODULE_SCREEN_NAME, config('plugins.hotel.email'));
        });

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            LanguageAdvancedManager::registerModule(Room::class, [
                'name',
                'description',
                'content',
            ]);

            LanguageAdvancedManager::registerModule(RoomCategory::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(Amenity::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(Food::class, [
                'name',
                'description',
                'content',
            ]);

            LanguageAdvancedManager::registerModule(FoodType::class, [
                'name',
            ]);

            LanguageAdvancedManager::registerModule(Feature::class, [
                'name',
                'description',
            ]);

            LanguageAdvancedManager::registerModule(Service::class, [
                'name',
                'description',
                'content',
            ]);

            LanguageAdvancedManager::registerModule(Place::class, [
                'name',
                'distance',
                'description',
                'content',
            ]);
        }

        SiteMapManager::registerKey(['rooms']);

        $this->app->register(EventServiceProvider::class);

        $this->app->booted(function (): void {
            $this->app->register(HookServiceProvider::class);

            SeoHelper::registerModule([
                Room::class,
                RoomCategory::class,
                Service::class,
                Place::class,
            ]);

            if (
                defined('SOCIAL_LOGIN_MODULE_SCREEN_NAME') &&
                Route::has('customer.login') &&
                Route::has('public.index')
            ) {
                SocialService::registerModule([
                    'guard' => 'customer',
                    'model' => Customer::class,
                    'login_url' => route('customer.login'),
                    'redirect_url' => route('public.index'),
                ]);
            }

            add_filter('social_login_before_saving_account', function ($data, $oAuth, $providerData) {
                if (Arr::get($providerData, 'model') == Customer::class && Arr::get($providerData, 'guard') == 'customer') {
                    $firstName = implode(' ', explode(' ', $oAuth->getName(), -1));
                    Arr::forget($data, 'name');
                    $data = array_merge($data, [
                        'first_name' => $firstName,
                        'last_name' => trim(str_replace($firstName, '', $oAuth->getName())),
                    ]);
                }

                return $data;
            }, 49, 3);
        });

        if (is_plugin_active('captcha')) {
            Captcha::registerFormSupport(LoginForm::class, LoginRequest::class, trans('plugins/hotel::hotel.login_form'));
            Captcha::registerFormSupport(RegisterForm::class, RegisterRequest::class, trans('plugins/hotel::hotel.register_form'));
            Captcha::registerFormSupport(ForgotPasswordForm::class, ForgotPasswordRequest::class, trans('plugins/hotel::hotel.forgot_password_form'));
            Captcha::registerFormSupport(ResetPasswordForm::class, ResetPasswordRequest::class, trans('plugins/hotel::hotel.reset_password_form'));
        }
    }
}
