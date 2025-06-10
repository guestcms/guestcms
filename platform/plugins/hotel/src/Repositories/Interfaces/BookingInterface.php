<?php

namespace Guestcms\Hotel\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;

interface BookingInterface extends RepositoryInterface
{
    public function getPendingBookings(array $select = ['*'], array $with = []);

    public function countPendingBookings(): int;
}
