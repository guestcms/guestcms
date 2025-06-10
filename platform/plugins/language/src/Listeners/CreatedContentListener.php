<?php

namespace Guestcms\Language\Listeners;

use Guestcms\Base\Events\CreatedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Language\Facades\Language;
use Exception;

class CreatedContentListener
{
    public function handle(CreatedContentEvent $event): void
    {
        try {
            if ($event->request->input('language')) {
                Language::saveLanguage($event->screen, $event->request, $event->data);
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
