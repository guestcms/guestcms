<?php

namespace Guestcms\ACL\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;

interface UserInterface extends RepositoryInterface
{
    public function getUniqueUsernameFromEmail(string $email): string;
}
