<?php

namespace Guestcms\Hotel\Events;

use Guestcms\Base\Events\Event;
use Guestcms\Hotel\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated extends Event
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public Booking $booking)
    {
    }
}
