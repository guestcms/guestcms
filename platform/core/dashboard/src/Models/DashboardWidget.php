<?php

namespace Guestcms\Dashboard\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DashboardWidget extends BaseModel
{
    protected $table = 'dashboard_widgets';

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => SafeContent::class,
    ];

    protected static function booted(): void
    {
        static::deleted(fn (DashboardWidget $widget) => $widget->settings()->delete());
    }

    public function settings(): HasMany
    {
        return $this->hasMany(DashboardWidgetSetting::class, 'widget_id', 'id');
    }
}
