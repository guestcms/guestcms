<?php

namespace Guestcms\Menu\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface MenuNodeInterface extends RepositoryInterface
{
    public function getByMenuId(int|string $menuId, int|string|null $parentId, array $select = ['*'], array $with = ['child']): Collection;
}
