<?php

namespace Guestcms\Theme\Events;

use Guestcms\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class RenderingHomePageEvent extends Event
{
    use SerializesModels;
}
