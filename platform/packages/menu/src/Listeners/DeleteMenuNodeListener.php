<?php

namespace Guestcms\Menu\Listeners;

use Guestcms\Base\Contracts\BaseModel;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Menu\Facades\Menu;
use Guestcms\Menu\Models\MenuNode;

class DeleteMenuNodeListener
{
    public function handle(DeletedContentEvent $event): void
    {
        if (
            ! $event->data instanceof BaseModel ||
            ! in_array($event->data::class, Menu::getMenuOptionModels())
        ) {
            return;
        }

        MenuNode::query()
            ->where([
                'reference_id' => $event->data->getKey(),
                'reference_type' => $event->data::class,
            ])
            ->delete();
    }
}
