<?php

namespace Guestcms\Gallery\Listeners;

use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Gallery\Facades\Gallery;
use Exception;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            Gallery::deleteGallery($event->data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
