<?php

namespace Guestcms\Hotel\Events;

use Guestcms\Base\Events\Event;
use Guestcms\Hotel\Models\Booking;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingStatusChanged extends Event
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public string $oldStatus, public Booking $booking)
    {
    }
}
