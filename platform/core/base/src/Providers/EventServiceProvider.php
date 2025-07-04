<?php

namespace Guestcms\Base\Providers;

use Guestcms\ACL\Events\RoleAssignmentEvent;
use Guestcms\ACL\Events\RoleUpdateEvent;
use Guestcms\Base\Events\AdminNotificationEvent;
use Guestcms\Base\Events\BeforeEditContentEvent;
use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Events\PanelSectionsRendering;
use Guestcms\Base\Events\SendMailEvent;
use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Base\Events\UpdatedEvent;
use Guestcms\Base\Facades\AdminHelper;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Facades\MetaBox;
use Guestcms\Base\Http\Middleware\AdminLocaleMiddleware;
use Guestcms\Base\Http\Middleware\CoreMiddleware;
use Guestcms\Base\Http\Middleware\DisableInDemoModeMiddleware;
use Guestcms\Base\Http\Middleware\EnsureLicenseHasBeenActivated;
use Guestcms\Base\Http\Middleware\HttpsProtocolMiddleware;
use Guestcms\Base\Http\Middleware\LocaleMiddleware;
use Guestcms\Base\Listeners\AdminNotificationListener;
use Guestcms\Base\Listeners\BeforeEditContentListener;
use Guestcms\Base\Listeners\ClearDashboardMenuCaches;
use Guestcms\Base\Listeners\ClearDashboardMenuCachesForLoggedUser;
use Guestcms\Base\Listeners\CreatedContentListener;
use Guestcms\Base\Listeners\DeletedContentListener;
use Guestcms\Base\Listeners\PushDashboardMenuToSystemPanel;
use Guestcms\Base\Listeners\SendMailListener;
use Guestcms\Base\Listeners\UpdatedContentListener;
use Guestcms\Base\Models\AdminNotification;
use Guestcms\Dashboard\Events\RenderingDashboardWidgets;
use Guestcms\Support\Http\Middleware\BaseMiddleware;
use Guestcms\Support\Services\Cache\Cache;
use Illuminate\Auth\Events\Login;
use Illuminate\Config\Repository;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SendMailEvent::class => [
            SendMailListener::class,
        ],
        CreatedContentEvent::class => [
            CreatedContentListener::class,
        ],
        UpdatedContentEvent::class => [
            UpdatedContentListener::class,
        ],
        DeletedContentEvent::class => [
            DeletedContentListener::class,
        ],
        BeforeEditContentEvent::class => [
            BeforeEditContentListener::class,
        ],
        AdminNotificationEvent::class => [
            AdminNotificationListener::class,
        ],
        UpdatedEvent::class => [
            ClearDashboardMenuCaches::class,
        ],
        Login::class => [
            ClearDashboardMenuCachesForLoggedUser::class,
        ],
        RoleAssignmentEvent::class => [
            ClearDashboardMenuCaches::class,
        ],
        RoleUpdateEvent::class => [
            ClearDashboardMenuCaches::class,
        ],
    ];

    public function boot(): void
    {
        $events = $this->app['events'];

        $events->listen(RouteMatched::class, function (): void {
            /**
             * @var Router $router
             */
            $router = $this->app['router'];

            $router->pushMiddlewareToGroup('web', LocaleMiddleware::class);
            $router->pushMiddlewareToGroup('web', AdminLocaleMiddleware::class);
            $router->pushMiddlewareToGroup('web', HttpsProtocolMiddleware::class);
            $router->aliasMiddleware('preventDemo', DisableInDemoModeMiddleware::class);
            $router->middlewareGroup('core', [CoreMiddleware::class]);

            $this->app->extend('core.middleware', function ($middleware) {
                return array_merge($middleware, [
                    EnsureLicenseHasBeenActivated::class,
                ]);
            });

            add_filter(BASE_FILTER_TOP_HEADER_LAYOUT, function ($options) {
                try {
                    $cache = Cache::make(AdminNotification::class);

                    if ($cache->has('admin-notifications-count')) {
                        $countNotificationUnread = $cache->get('admin-notifications-count');
                    } else {
                        $countNotificationUnread = AdminNotification::countUnread();

                        $cache->put('admin-notifications-count', $countNotificationUnread, 60 * 60 * 24);
                    }
                } catch (Throwable) {
                    $countNotificationUnread = 0;
                }

                return $options . view('core/base::notification.nav-item', compact('countNotificationUnread'));
            }, 99);

            add_filter(BASE_FILTER_FOOTER_LAYOUT_TEMPLATE, function ($html) {
                if (! Auth::guard()->check()) {
                    return $html;
                }

                return $html . view('core/base::notification.notification');
            }, 99);

            add_action(BASE_ACTION_META_BOXES, [MetaBox::class, 'doMetaBoxes'], 8, 2);

            $this->disableCsrfProtection();
        });

        $events->listen(MigrationsStarted::class, function (): void {
            rescue(function (): void {
                if (DB::getDefaultConnection() === 'mysql') {
                    DB::statement('SET SESSION sql_require_primary_key=0');
                }
            }, report: false);
        });

        $events->listen(['cache:cleared'], function (): void {
            $files = $this->app['files'];

            $files->delete(storage_path('cache_keys.json'));

            $files->deleteDirectory(storage_path('app/chunks'));
            $files->deleteDirectory(storage_path('app/data-synchronize'));
            $files->deleteDirectory(storage_path('app/marketplace'));
        });

        $events->listen(PanelSectionsRendering::class, PushDashboardMenuToSystemPanel::class);

        if ($this->app->isLocal()) {
            DB::listen(function (QueryExecuted $queryExecuted): void {
                if ($queryExecuted->time < 500) {
                    return;
                }

                Log::warning(sprintf('DB query exceeded %s ms. SQL: %s', $queryExecuted->time, $queryExecuted->sql));
            });
        }

        $this->app['events']->listen(RenderingDashboardWidgets::class, function (): void {
            add_filter(DASHBOARD_FILTER_ADMIN_NOTIFICATIONS, function (?string $html) {

                $size = File::size(storage_path('framework/cache'));

                if ($size < 1024 * 1024 * 100) {
                    return $html;
                }

                $size = BaseHelper::humanFilesize($size);

                return $html . view('core/base::system.partials.cache-too-large-alert', compact('size'))->render();
            }, 5);
        });
    }

    protected function disableCsrfProtection(): void
    {
        /**
         * @var Repository $config
         */
        $config = $this->app['config'];

        if (
            BaseHelper::hasDemoModeEnabled()
            || $config->get('core.base.general.disable_verify_csrf_token', false)
            || ($this->app->environment('production') && AdminHelper::isInAdmin())
        ) {
            $this->app->instance(ValidateCsrfToken::class, new BaseMiddleware());
        }
    }
}
