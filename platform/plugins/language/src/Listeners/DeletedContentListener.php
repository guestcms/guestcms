<?php

namespace Guestcms\Language\Listeners;

use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Language\Facades\Language;
use Exception;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            Language::deleteLanguage($event->screen, $event->data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
