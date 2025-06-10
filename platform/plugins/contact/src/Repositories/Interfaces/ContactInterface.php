<?php

namespace Guestcms\Contact\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface ContactInterface extends RepositoryInterface
{
    public function getUnread(array $select = ['*']): Collection;

    public function countUnread(): int;
}
