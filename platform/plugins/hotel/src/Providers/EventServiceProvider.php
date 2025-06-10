<?php

namespace Guestcms\Hotel\Providers;

use Guestcms\Base\Events\RenderingAdminWidgetEvent;
use Guestcms\Hotel\Events\BookingCreated;
use Guestcms\Hotel\Events\BookingStatusChanged;
use Guestcms\Hotel\Listeners\AddSitemapListener;
use Guestcms\Hotel\Listeners\GenerateInvoiceListener;
use Guestcms\Hotel\Listeners\RegisterBookingReportsWidget;
use Guestcms\Hotel\Listeners\SendConfirmationEmail;
use Guestcms\Hotel\Listeners\SendStatusChangedNotificationListener;
use Guestcms\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        RenderingSiteMapEvent::class => [
            AddSitemapListener::class,
        ],
        BookingCreated::class => [
            GenerateInvoiceListener::class,
            SendConfirmationEmail::class,
        ],
        BookingStatusChanged::class => [
            SendStatusChangedNotificationListener::class,
        ],
        RenderingAdminWidgetEvent::class => [
            RegisterBookingReportsWidget::class,
        ],
     ];
}
