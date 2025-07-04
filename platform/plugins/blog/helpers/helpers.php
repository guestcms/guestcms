<?php

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Models\BaseModel;
use Guestcms\Base\Supports\SortItemsWithChildrenHelper;
use Guestcms\Blog\Repositories\Interfaces\CategoryInterface;
use Guestcms\Blog\Repositories\Interfaces\PostInterface;
use Guestcms\Blog\Repositories\Interfaces\TagInterface;
use Guestcms\Blog\Supports\PostFormat;
use Guestcms\Page\Models\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

if (! function_exists('get_featured_posts')) {
    function get_featured_posts(int $limit, array $with = []): Collection
    {
        return app(PostInterface::class)->getFeatured($limit, $with);
    }
}

if (! function_exists('get_latest_posts')) {
    function get_latest_posts(int $limit, array $excepts = [], array $with = []): Collection
    {
        return app(PostInterface::class)->getListPostNonInList($excepts, $limit, $with);
    }
}

if (! function_exists('get_related_posts')) {
    function get_related_posts(int|string $id, int $limit): Collection
    {
        return app(PostInterface::class)->getRelated($id, $limit);
    }
}

if (! function_exists('get_posts_by_category')) {
    function get_posts_by_category(int|string $categoryId, int $paginate = 12, int $limit = 0): Collection|LengthAwarePaginator
    {
        return app(PostInterface::class)->getByCategory($categoryId, $paginate, $limit);
    }
}

if (! function_exists('get_posts_by_tag')) {
    function get_posts_by_tag(string $slug, int $paginate = 12): Collection|LengthAwarePaginator
    {
        return app(PostInterface::class)->getByTag($slug, $paginate);
    }
}

if (! function_exists('get_posts_by_user')) {
    function get_posts_by_user(int|string $authorId, int $paginate = 12): Collection|LengthAwarePaginator
    {
        return app(PostInterface::class)->getByUserId($authorId, $paginate);
    }
}

if (! function_exists('get_all_posts')) {
    function get_all_posts(
        bool $active = true,
        int $perPage = 12,
        array $with = ['slugable', 'categories', 'categories.slugable', 'author']
    ): Collection|LengthAwarePaginator {
        return app(PostInterface::class)->getAllPosts($perPage, $active, $with);
    }
}

if (! function_exists('get_recent_posts')) {
    function get_recent_posts(int $limit): Collection|LengthAwarePaginator
    {
        return app(PostInterface::class)->getRecentPosts($limit);
    }
}

if (! function_exists('get_featured_categories')) {
    function get_featured_categories(int $limit, array $with = []): Collection|LengthAwarePaginator
    {
        return app(CategoryInterface::class)->getFeaturedCategories($limit, $with);
    }
}

if (! function_exists('get_all_categories')) {
    function get_all_categories(array $condition = [], array $with = []): Collection|LengthAwarePaginator
    {
        return app(CategoryInterface::class)->getAllCategories($condition, $with);
    }
}

if (! function_exists('get_all_tags')) {
    function get_all_tags(bool $active = true): Collection|LengthAwarePaginator
    {
        return app(TagInterface::class)->getAllTags($active);
    }
}

if (! function_exists('get_popular_tags')) {
    function get_popular_tags(
        int $limit = 10,
        array $with = ['slugable'],
        array $withCount = ['posts']
    ): Collection|LengthAwarePaginator {
        return app(TagInterface::class)->getPopularTags($limit, $with, $withCount);
    }
}

if (! function_exists('get_popular_posts')) {
    function get_popular_posts(int $limit = 10, array $args = []): Collection|LengthAwarePaginator
    {
        return app(PostInterface::class)->getPopularPosts($limit, $args);
    }
}

if (! function_exists('get_popular_categories')) {
    function get_popular_categories(
        int $limit = 10,
        array $with = ['slugable'],
        array $withCount = ['posts']
    ): Collection|LengthAwarePaginator {
        return app(CategoryInterface::class)->getPopularCategories($limit, $with, $withCount);
    }
}

if (! function_exists('get_category_by_id')) {
    function get_category_by_id(int|string $id): ?BaseModel
    {
        return app(CategoryInterface::class)->getCategoryById($id);
    }
}

if (! function_exists('get_categories')) {
    function get_categories(array $args = []): array
    {
        $indent = Arr::get($args, 'indent', '——');

        $repo = app(CategoryInterface::class);

        $categories = $repo->getCategories(Arr::get($args, 'select', ['*']), [
            'is_default' => 'DESC',
            'order' => 'ASC',
            'created_at' => 'DESC',
        ], Arr::get($args, 'condition', ['status' => BaseStatusEnum::PUBLISHED]));

        $categories = sort_item_with_children($categories);

        foreach ($categories as $category) {
            $depth = (int) $category->depth;
            $indentText = str_repeat($indent, $depth);
            $category->indent_text = $indentText;
        }

        return $categories;
    }
}

if (! function_exists('get_categories_with_children')) {
    function get_categories_with_children(): array
    {
        $categories = app(CategoryInterface::class)
            ->getAllCategoriesWithChildren(['status' => BaseStatusEnum::PUBLISHED], [], ['id', 'name', 'parent_id']);

        return app(SortItemsWithChildrenHelper::class)
            ->setChildrenProperty('child_cats')
            ->setItems($categories)
            ->sort();
    }
}

if (! function_exists('register_post_format')) {
    function register_post_format(array $formats): void
    {
        PostFormat::registerPostFormat($formats);
    }
}

if (! function_exists('get_post_formats')) {
    function get_post_formats(bool $toArray = false): array
    {
        return PostFormat::getPostFormats($toArray);
    }
}

if (! function_exists('get_blog_page_id')) {
    function get_blog_page_id(): ?string
    {
        return theme_option('blog_page_id', setting('blog_page_id'));
    }
}

if (! function_exists('get_blog_page_url')) {
    function get_blog_page_url(): string
    {
        $blogPageId = (int) theme_option('blog_page_id', setting('blog_page_id'));

        if (! $blogPageId) {
            return BaseHelper::getHomepageUrl();
        }

        $blogPage = Page::query()->find($blogPageId);

        if (! $blogPage) {
            return BaseHelper::getHomepageUrl();
        }

        return $blogPage->url;
    }
}
