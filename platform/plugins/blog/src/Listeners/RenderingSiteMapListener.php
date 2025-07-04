<?php

namespace Guestcms\Blog\Listeners;

use Guestcms\Blog\Models\Category;
use Guestcms\Blog\Models\Post;
use Guestcms\Blog\Models\Tag;
use Guestcms\Theme\Events\RenderingSiteMapEvent;
use Guestcms\Theme\Facades\SiteMapManager;
use Illuminate\Support\Arr;

class RenderingSiteMapListener
{
    public function handle(RenderingSiteMapEvent $event): void
    {
        if ($key = $event->key) {
            switch ($key) {
                case 'blog-categories':
                    $categories = Category::query()
                        ->with('slugable')
                        ->wherePublished()
                        ->select(['id', 'name', 'updated_at'])->latest()
                        ->get();

                    foreach ($categories as $category) {
                        SiteMapManager::add($category->url, $category->updated_at, '0.8');
                    }

                    break;
                case 'blog-tags':
                    $tags = Tag::query()
                        ->with('slugable')
                        ->wherePublished()
                        ->orderByDesc('created_at')
                        ->select(['id', 'name', 'updated_at'])
                        ->get();

                    foreach ($tags as $tag) {
                        SiteMapManager::add($tag->url, $tag->updated_at, '0.3', 'weekly');
                    }

                    break;
            }

            // Handle posts with pagination using new standardized pattern
            $paginationData = SiteMapManager::extractPaginationDataByPattern($key, 'blog-posts', 'monthly-archive');

            if ($paginationData) {
                $matches = $paginationData['matches'];
                $year = Arr::get($matches, 1);
                $month = Arr::get($matches, 2);

                if ($year && $month) {
                    $posts = Post::query()
                        ->wherePublished()
                        ->whereYear('created_at', $year)
                        ->whereMonth('created_at', $month)
                        ->latest('updated_at')
                        ->select(['id', 'name', 'updated_at'])
                        ->with(['slugable'])
                        ->skip($paginationData['offset'])
                        ->take($paginationData['limit'])
                        ->get();

                    foreach ($posts as $post) {
                        if (! $post->slugable) {
                            continue;
                        }

                        SiteMapManager::add($post->url, $post->updated_at, '0.8');
                    }
                }
            }

            return;
        }

        // Generate sitemap indexes using the new SiteMapManager pagination functionality
        $posts = Post::query()
            ->selectRaw('YEAR(created_at) as created_year, MONTH(created_at) as created_month, MAX(created_at) as created_at, COUNT(*) as post_count')
            ->wherePublished()
            ->groupBy('created_year', 'created_month')
            ->orderByDesc('created_year')
            ->orderByDesc('created_month')
            ->get();

        if ($posts->isNotEmpty()) {
            foreach ($posts as $post) {
                $formattedMonth = str_pad($post->created_month, 2, '0', STR_PAD_LEFT);
                $baseKey = sprintf('blog-posts-%s-%s', $post->created_year, $formattedMonth);

                // Use the new createPaginatedSitemaps method
                SiteMapManager::createPaginatedSitemaps($baseKey, $post->post_count, $post->created_at);
            }
        }

        $categoryLastUpdated = Category::query()
            ->wherePublished()
            ->latest('updated_at')
            ->value('updated_at');

        if ($categoryLastUpdated) {
            SiteMapManager::addSitemap(SiteMapManager::route('blog-categories'), $categoryLastUpdated);
        }

        $tagLastUpdated = Tag::query()
            ->wherePublished()
            ->latest('updated_at')
            ->value('updated_at');

        if ($tagLastUpdated) {
            SiteMapManager::addSitemap(SiteMapManager::route('blog-tags'), $tagLastUpdated);
        }
    }
}
