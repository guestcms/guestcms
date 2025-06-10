<?php

namespace Guestcms\Language\Listeners;

use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Language\Facades\Language;
use Exception;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
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
