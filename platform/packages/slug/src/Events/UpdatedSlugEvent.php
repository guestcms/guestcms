<?php

namespace Guestcms\Slug\Events;

use Guestcms\Base\Events\Event;
use Guestcms\Slug\Models\Slug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatedSlugEvent extends Event
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public bool|Model|null $data, public Slug $slug)
    {
    }
}
