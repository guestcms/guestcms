<?php

namespace Guestcms\SeoHelper\Listeners;

use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Exception;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            SeoHelper::deleteMetaData($event->screen, $event->data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
