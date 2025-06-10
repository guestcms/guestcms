<?php

namespace Guestcms\Payment\Events;

use Guestcms\Base\Events\Event;
use Illuminate\Foundation\Events\Dispatchable;

class PaymentWebhookReceived extends Event
{
    use Dispatchable;

    public function __construct(public string $chargeId, public array $data = [])
    {
    }
}
