<?php

namespace Guestcms\Base\Events;

use Guestcms\Base\Widgets\Contracts\AdminWidget;
use Illuminate\Foundation\Events\Dispatchable;

class RenderingAdminWidgetEvent
{
    use Dispatchable;

    public function __construct(public AdminWidget $widget)
    {
    }
}
