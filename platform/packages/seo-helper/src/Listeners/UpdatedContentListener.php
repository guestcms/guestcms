<?php

namespace Guestcms\SeoHelper\Listeners;

use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\SeoHelper\Facades\SeoHelper;
use Exception;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
    {
        try {
            SeoHelper::saveMetaData($event->screen, $event->request, $event->data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
