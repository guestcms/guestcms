<?php

namespace Guestcms\Hotel\Listeners;

use Guestcms\Hotel\Events\BookingCreated;
use Guestcms\Hotel\Supports\InvoiceHelper;

class GenerateInvoiceListener
{
    public function handle(BookingCreated $event): void
    {
        (new InvoiceHelper())->store($event->booking);
    }
}
