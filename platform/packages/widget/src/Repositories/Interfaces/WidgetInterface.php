<?php

namespace Guestcms\Widget\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface WidgetInterface extends RepositoryInterface
{
    public function getByTheme(string $theme): Collection;
}
