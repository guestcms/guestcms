<?php

namespace Guestcms\AuditLog\Listeners;

use Guestcms\AuditLog\AuditLog;
use Guestcms\AuditLog\Events\AuditHandlerEvent;
use Guestcms\Base\Events\DeletedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Exception;

class DeletedContentListener
{
    public function handle(DeletedContentEvent $event): void
    {
        try {
            if ($event->data->getKey()) {
                $model = $event->screen;

                event(new AuditHandlerEvent(
                    $model,
                    'deleted',
                    $event->data->getKey(),
                    AuditLog::getReferenceName($model, $event->data),
                    'danger'
                ));
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
