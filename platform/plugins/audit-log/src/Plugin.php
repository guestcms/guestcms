<?php

namespace Guestcms\AuditLog;

use Guestcms\Dashboard\Models\DashboardWidget;
use Guestcms\PluginManagement\Abstracts\PluginOperationAbstract;
use Guestcms\Widget\Models\Widget;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('audit_histories');

        Widget::query()
            ->where('widget_id', 'widget_audit_logs')
            ->each(fn (DashboardWidget $dashboardWidget) => $dashboardWidget->delete());
    }
}
