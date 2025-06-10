<?php

namespace Guestcms\Theme\Events;

use Guestcms\Base\Events\Event;

class RenderingSiteMapEvent extends Event
{
    public function __construct(public ?string $key = null)
    {
    }
}
