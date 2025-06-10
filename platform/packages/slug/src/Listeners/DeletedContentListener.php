<?php

namespace Guestcms\Slug\Listeners;

use Guestcms\Base\Contracts\BaseModel;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Slug\Models\Slug;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        if ($event->data instanceof BaseModel && SlugHelper::isSupportedModel($event->data::class)) {
            Slug::query()->where([
                'reference_id' => $event->data->getKey(),
                'reference_type' => $event->data::class,
            ])->delete();
        }
    }
}
