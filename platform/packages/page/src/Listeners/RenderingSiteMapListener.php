<?php

namespace Guestcms\Page\Listeners;

use Guestcms\Base\Supports\RepositoryHelper;
use Guestcms\Page\Models\Page;
use Guestcms\Theme\Events\RenderingSiteMapEvent;
use Guestcms\Theme\Facades\SiteMapManager;

class RenderingSiteMapListener
{
    public function handle(RenderingSiteMapEvent $event): void
    {
        if ($event->key == 'pages') {
            $pages = Page::query()
                ->wherePublished()->latest()
                ->select(['id', 'name', 'updated_at'])
                ->with('slugable');

            $pages = RepositoryHelper::applyBeforeExecuteQuery($pages, new Page())->get();

            foreach ($pages as $page) {
                SiteMapManager::add($page->url, $page->updated_at, '0.8');
            }
        }
    }
}
