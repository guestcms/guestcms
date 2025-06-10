<?php

namespace Guestcms\LanguageAdvanced\Listeners;

use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Base\Models\BaseModel;
use Guestcms\Support\Services\Cache\Cache;

class ClearCacheAfterUpdateData
{
    public function handle(UpdatedContentEvent $event): void
    {
        if (! $event->data instanceof BaseModel) {
            return;
        }

        Cache::make($event->data::class)->flush();
    }
}
