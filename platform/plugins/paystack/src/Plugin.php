<?php

namespace Guestcms\Paystack;

use Guestcms\PluginManagement\Abstracts\PluginOperationAbstract;
use Guestcms\Setting\Facades\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::delete([
            'payment_paystack_name',
            'payment_paystack_description',
            'payment_paystack_secret',
            'payment_paystack_merchant_email',
            'payment_paystack_status',
        ]);
    }
}
