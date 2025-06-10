<?php

namespace Guestcms\ACL\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;

interface RoleInterface extends RepositoryInterface
{
    public function createSlug(string $name, int|string $id): string;
}
