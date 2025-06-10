<?php

namespace Guestcms\SeoHelper\Listeners;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Exception;

class CreatedContentListener
{
    public function handle(CreatedContentEvent $event): void
    {
        try {
            SeoHelper::saveMetaData($event->screen, $event->request, $event->data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
