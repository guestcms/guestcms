<?php

namespace Guestcms\AuditLog\Listeners;

use Guestcms\AuditLog\AuditLog;
use Guestcms\AuditLog\Events\AuditHandlerEvent;
use Guestcms\Base\Events\UpdatedContentEvent;
use Guestcms\Base\Facades\BaseHelper;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UpdatedContentListener
{
    public function handle(UpdatedContentEvent $event): void
    {
        try {
            if ($event->data->getKey()) {
                $model = $event->screen;

                if ($model === 'form') {
                    $model = strtolower(Str::afterLast(get_class($event->data), '\\'));
                }

                event(new AuditHandlerEvent(
                    $model,
                    $model === 'user' && $event->data->getKey() == Auth::id() ? 'has updated his profile' : 'updated',
                    $event->data->getKey(),
                    AuditLog::getReferenceName($model, $event->data),
                    'primary'
                ));
            }
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
