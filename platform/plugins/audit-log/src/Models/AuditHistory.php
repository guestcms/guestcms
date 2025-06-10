<?php

namespace Guestcms\AuditLog\Models;

use Guestcms\ACL\Models\User;
use Guestcms\Base\Models\BaseModel;
use Guestcms\Base\Models\BaseQueryBuilder;
use Guestcms\Ecommerce\Models\Customer;
use Guestcms\Setting\Enums\DataRetentionPeriod;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;

class AuditHistory extends BaseModel
{
    use MassPrunable;

    protected $table = 'audit_histories';

    protected $fillable = [
        'user_agent',
        'ip_address',
        'module',
        'action',
        'user_id',
        'user_type',
        'actor_id',
        'actor_type',
        'reference_id',
        'reference_name',
        'type',
        'request',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    public function actor(): MorphTo
    {
        return $this->morphTo('actor');
    }

    public function getUserNameAttribute(): string
    {
        if (! $this->user_type || ! class_exists($this->user_type)) {
            return trans('plugins/audit-log::history.system');
        }

        if (! $this->user) {
            return trans('plugins/audit-log::history.system');
        }

        return $this->user->name;
    }

    public function getActorNameAttribute(): string
    {
        if (! $this->actor_type || ! class_exists($this->actor_type)) {
            return trans('plugins/audit-log::history.system');
        }

        if (! $this->actor) {
            return trans('plugins/audit-log::history.system');
        }

        return $this->actor->name;
    }

    public function getUserTypeLabelAttribute(): string
    {
        if (! $this->user_type || ! class_exists($this->user_type)) {
            return trans('plugins/audit-log::history.system');
        }

        return match ($this->user_type) {
            User::class => trans('plugins/audit-log::history.admin'),
            Customer::class => trans('plugins/audit-log::history.customer'),
            default => trans('plugins/audit-log::history.system'),
        };
    }

    public function getActorTypeLabelAttribute(): string
    {
        if (! $this->actor_type || ! class_exists($this->actor_type)) {
            return trans('plugins/audit-log::history.system');
        }

        return match ($this->actor_type) {
            User::class => trans('plugins/audit-log::history.admin'),
            Customer::class => trans('plugins/audit-log::history.customer'),
            default => trans('plugins/audit-log::history.system'),
        };
    }

    public function prunable(): Builder|BaseQueryBuilder
    {
        $days = setting('audit_log_data_retention_period', DataRetentionPeriod::ONE_MONTH);

        if ($days === DataRetentionPeriod::NEVER) {
            return $this->query()->where('id', '<', 0);
        }

        return $this->query()->where('created_at', '<', Carbon::now()->subDays($days));
    }
}
