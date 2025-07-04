<?php

namespace Guestcms\Newsletter\Listeners;

use Guestcms\Base\Facades\EmailHandler;
use Guestcms\Base\Facades\Html;
use Guestcms\Newsletter\Events\SubscribeNewsletterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\URL;

class SendEmailNotificationAboutNewSubscriberListener implements ShouldQueue
{
    public function handle(SubscribeNewsletterEvent $event): void
    {
        $unsubscribeUrl = URL::signedRoute('public.newsletter.unsubscribe', ['user' => $event->newsletter->id]);

        $mailer = EmailHandler::setModule(NEWSLETTER_MODULE_SCREEN_NAME)->setVariableValues([
            'newsletter_name' => $event->newsletter->name ?? 'N/A',
            'newsletter_email' => $event->newsletter->email,
            'newsletter_unsubscribe_link' => Html::link($unsubscribeUrl, __('here'))->toHtml(),
            'newsletter_unsubscribe_url' => $unsubscribeUrl,
        ]);

        $mailer->sendUsingTemplate('subscriber_email', $event->newsletter->email);

        $mailer->sendUsingTemplate('admin_email');
    }
}
