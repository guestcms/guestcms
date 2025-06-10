<?php

namespace Guestcms\Base\Listeners;

use Guestcms\ACL\Models\User;
use Guestcms\Base\Facades\DashboardMenu;
use Illuminate\Auth\Events\Login;

class ClearDashboardMenuCachesForLoggedUser
{
    public function handle(Login $event): void
    {
        if (! $event->user instanceof User) {
            return;
        }

        DashboardMenu::default()->clearCachesForCurrentUser();
    }
}
