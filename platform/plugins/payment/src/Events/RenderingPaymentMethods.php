<?php

namespace Guestcms\Payment\Events;

use Guestcms\Base\Events\Event;

class RenderingPaymentMethods extends Event
{
    public function __construct(public array $methods)
    {
    }
}
