<?php

namespace Guestcms\Gallery\Repositories\Interfaces;

use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;

interface GalleryInterface extends RepositoryInterface
{
    public function getAll(array $with = ['slugable', 'user'], int $limit = 0): Collection;

    public function getDataSiteMap(): Collection;

    public function getFeaturedGalleries(int $limit, array $with = ['slugable', 'user']): Collection;
}
