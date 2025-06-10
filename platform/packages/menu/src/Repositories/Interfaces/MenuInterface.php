<?php

namespace Guestcms\Menu\Repositories\Interfaces;

use Guestcms\Base\Models\BaseModel;
use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;

interface MenuInterface extends RepositoryInterface
{
    public function findBySlug(string $slug, bool $active, array $select = [], array $with = []): ?BaseModel;

    public function createSlug(string $name): string;
}
