<?php

namespace Guestcms\Blog\Repositories\Interfaces;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;
use Guestcms\Blog\Models\Category;
use Guestcms\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryInterface extends RepositoryInterface
{
    public function getDataSiteMap(): Collection;

    public function getFeaturedCategories(?int $limit, array $with = []): Collection;

    public function getAllCategories(array $condition = [], array $with = []): Collection;

    public function getCategoryById(int|string|null $id): ?Category;

    public function getCategories(array $select, array $orderBy, array $conditions = ['status' => BaseStatusEnum::PUBLISHED]): Collection;

    public function getAllRelatedChildrenIds(int|string|null|BaseModel $id): array;

    public function getAllCategoriesWithChildren(array $condition = [], array $with = [], array $select = ['*']): Collection;

    public function getFilters(array $filters): LengthAwarePaginator;

    public function getPopularCategories(int $limit, array $with = ['slugable'], array $withCount = ['posts']): Collection;
}
