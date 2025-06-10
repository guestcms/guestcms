<?php

namespace Guestcms\Theme\Events;

use Guestcms\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class ThemeRemoveEvent extends Event
{
    use SerializesModels;

    public function __construct(public string $theme)
    {
    }
}
