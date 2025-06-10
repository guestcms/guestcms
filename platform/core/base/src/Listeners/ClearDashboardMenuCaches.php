<?php

namespace Guestcms\Base\Listeners;

use Guestcms\Base\Facades\DashboardMenu;

class ClearDashboardMenuCaches
{
    public function handle(): void
    {
        DashboardMenu::clearCaches();
    }
}
