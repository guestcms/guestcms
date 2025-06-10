<?php

namespace Guestcms\Hotel\Repositories\Eloquent;

use Guestcms\Hotel\Enums\BookingStatusEnum;
use Guestcms\Hotel\Repositories\Interfaces\BookingInterface;
use Guestcms\Support\Repositories\Eloquent\RepositoriesAbstract;

class BookingRepository extends RepositoriesAbstract implements BookingInterface
{
    public function getPendingBookings(array $select = ['*'], array $with = [])
    {
        $data = $this->model->where('status', BookingStatusEnum::PENDING)
            ->select($select)
            ->with($with)
            ->get();

        $this->resetModel();

        return $data;
    }

    public function countPendingBookings(): int
    {
        $data = $this->model->where('status', BookingStatusEnum::PENDING)->count();

        $this->resetModel();

        return $data;
    }
}
