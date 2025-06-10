<?php

namespace Guestcms\Gallery\Listeners;

use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Gallery\Facades\Gallery;
use Exception;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
    {
        try {
            Gallery::saveGallery($event->request, $event->data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
