<?php

namespace Guestcms\Hotel\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;

interface PlaceInterface extends RepositoryInterface
{
    public function getRelatedPlaces(int $placeId, $limit = 3);
}
