<?php

namespace Guestcms\Newsletter\Providers;

use Guestcms\Newsletter\Events\SubscribeNewsletterEvent;
use Guestcms\Newsletter\Events\UnsubscribeNewsletterEvent;
use Guestcms\Newsletter\Listeners\AddSubscriberToMailchimpContactListListener;
use Guestcms\Newsletter\Listeners\AddSubscriberToSendGridContactListListener;
use Guestcms\Newsletter\Listeners\RemoveSubscriberToMailchimpContactListListener;
use Guestcms\Newsletter\Listeners\SendEmailNotificationAboutNewSubscriberListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SubscribeNewsletterEvent::class => [
            SendEmailNotificationAboutNewSubscriberListener::class,
            AddSubscriberToMailchimpContactListListener::class,
            AddSubscriberToSendGridContactListListener::class,
        ],
        UnsubscribeNewsletterEvent::class => [
            RemoveSubscriberToMailchimpContactListListener::class,
        ],
    ];
}
