<?php

namespace Guestcms\Gallery\Listeners;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Gallery\Facades\Gallery;
use Exception;

class CreatedContentListener
{
    public function handle(CreatedContentEvent $event): void
    {
        try {
            Gallery::saveGallery($event->request, $event->data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
