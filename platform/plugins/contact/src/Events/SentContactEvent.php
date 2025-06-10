<?php

namespace Guestcms\Contact\Events;

use Guestcms\Base\Events\Event;
use Guestcms\Base\Models\BaseModel;
use Illuminate\Queue\SerializesModels;

class SentContactEvent extends Event
{
    use SerializesModels;

    public function __construct(public bool|BaseModel|null $data)
    {
    }
}
