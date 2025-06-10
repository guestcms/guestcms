<?php

namespace Guestcms\Dashboard\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface DashboardWidgetSettingInterface extends RepositoryInterface
{
    public function getListWidget(): Collection;
}
