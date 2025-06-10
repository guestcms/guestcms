<?php

namespace Guestcms\Hotel\Listeners;

use Guestcms\Hotel\Models\Room;
use Guestcms\Theme\Events\RenderingSiteMapEvent;
use Guestcms\Theme\Facades\SiteMapManager;

class AddSitemapListener
{
    public function handle(RenderingSiteMapEvent $event): void
    {
        if ($event->key == 'rooms') {
            $roomLastUpdated = Room::query()
                ->wherePublished()
                ->latest('updated_at')
                ->value('updated_at');

            SiteMapManager::add(route('public.rooms'), $roomLastUpdated, '0.4', 'monthly');

            $rooms = Room::query()
                ->wherePublished()
                ->with(['slugable'])
                ->get();

            foreach ($rooms as $room) {
                SiteMapManager::add($room->url, $room->updated_at, '0.6');
            }
        }

        $roomLastUpdated = Room::query()
            ->wherePublished()
            ->latest('updated_at')
            ->value('updated_at');

        SiteMapManager::addSitemap(SiteMapManager::route('rooms'), $roomLastUpdated);
    }
}
