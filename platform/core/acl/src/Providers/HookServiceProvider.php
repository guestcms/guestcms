<?php

namespace Guestcms\ACL\Providers;

use Guestcms\ACL\Hooks\UserWidgetHook;
use Guestcms\Base\Supports\ServiceProvider;
use Guestcms\Dashboard\Events\RenderingDashboardWidgets;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['events']->listen(RenderingDashboardWidgets::class, function (): void {
            add_filter(DASHBOARD_FILTER_ADMIN_LIST, [UserWidgetHook::class, 'addUserStatsWidget'], 12, 2);
        });
    }
}
