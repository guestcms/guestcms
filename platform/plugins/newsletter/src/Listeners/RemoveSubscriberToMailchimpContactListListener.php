<?php

namespace Guestcms\Newsletter\Listeners;

use Guestcms\Newsletter\Events\UnsubscribeNewsletterEvent;
use Guestcms\Newsletter\Facades\Newsletter;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveSubscriberToMailchimpContactListListener implements ShouldQueue
{
    public function handle(UnsubscribeNewsletterEvent $event): void
    {
        if (! setting('enable_newsletter_contacts_list_api')) {
            return;
        }

        $mailchimpApiKey = setting('newsletter_mailchimp_api_key');
        $mailchimpListId = setting('newsletter_mailchimp_list_id');

        if (! $mailchimpApiKey || ! $mailchimpListId) {
            return;
        }

        Newsletter::driver('mailchimp')->unsubscribe($event->newsletter->email);
    }
}
