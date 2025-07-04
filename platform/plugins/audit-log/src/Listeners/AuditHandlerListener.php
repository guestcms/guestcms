<?php

namespace Guestcms\AuditLog\Listeners;

use Guestcms\AuditLog\Events\AuditHandlerEvent;
use Guestcms\AuditLog\Models\AuditHistory;
use Guestcms\Base\Facades\BaseHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AuditHandlerListener
{
    public function __construct(protected Request $request)
    {
    }

    public function handle(AuditHandlerEvent $event): void
    {
        try {
            $module = strtolower(Str::afterLast($event->module, '\\'));

            $data = [
                'user_agent' => $this->request->userAgent(),
                'ip_address' => $this->request->ip(),
                'module' => $module,
                'action' => $event->action,
                'user_id' => $this->request->user() ? $this->request->user()->getKey() : 0,
                'user_type' => $this->request->user() ? get_class($this->request->user()) : null,
                'actor_id' => $event->referenceUser,
                'actor_type' => $this->request->user() ? get_class($this->request->user()) : null,
                'reference_id' => $event->referenceId,
                'reference_name' => $event->referenceName,
                'type' => $event->type,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            if (! in_array($event->action, ['loggedin', 'password'])) {
                $data['request'] = json_encode($this->request->except([
                    'username',
                    'password',
                    're_password',
                    'new_password',
                    'current_password',
                    'password_confirmation',
                    '_token',
                    'token',
                    'refresh_token',
                    'remember_token',
                ]));
            }

            if (! Cache::has('pruned_audit_logs_table')) {
                (new AuditHistory())->pruneAll();

                Cache::put('pruned_audit_logs_table', 1, Carbon::now()->addDay());
            }

            AuditHistory::query()->insert($data);
        } catch (Exception $exception) {
            BaseHelper::logError($exception);
        }
    }
}
