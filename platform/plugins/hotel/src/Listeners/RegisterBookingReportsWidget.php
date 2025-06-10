<?php

namespace Guestcms\Hotel\Listeners;

use Guestcms\Base\Events\RenderingAdminWidgetEvent;
use Guestcms\Hotel\Widgets\BookingCard;
use Guestcms\Hotel\Widgets\BookingChart;
use Guestcms\Hotel\Widgets\CustomerCard;
use Guestcms\Hotel\Widgets\CustomerChart;
use Guestcms\Hotel\Widgets\RecentBookingsTable;
use Guestcms\Hotel\Widgets\ReportGeneralHtml;
use Guestcms\Hotel\Widgets\RevenueCard;
use Guestcms\Hotel\Widgets\RoomCard;

class RegisterBookingReportsWidget
{
    public function handle(RenderingAdminWidgetEvent $event): void
    {
        $event->widget
            ->register([
                RevenueCard::class,
                RoomCard::class,
                CustomerCard::class,
                BookingCard::class,
                CustomerChart::class,
                BookingChart::class,
                ReportGeneralHtml::class,
                RecentBookingsTable::class,
            ], 'booking-reports');
    }
}
