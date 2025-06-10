<?php

namespace Guestcms\Theme\Events;

use Guestcms\Base\Events\Event;
use Guestcms\Slug\Models\Slug;
use Illuminate\Queue\SerializesModels;

class RenderingSingleEvent extends Event
{
    use SerializesModels;

    public function __construct(public Slug $slug)
    {
    }
}
