<?php

namespace Guestcms\Payment\Events;

use Guestcms\Base\Events\Event;

class RenderedPaymentMethods extends Event
{
    public function __construct(public string $html)
    {
    }
}
