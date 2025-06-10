<?php

namespace Guestcms\PayPal;

use Guestcms\PluginManagement\Abstracts\PluginOperationAbstract;
use Guestcms\Setting\Facades\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Setting::delete([
            'payment_paypal_name',
            'payment_paypal_description',
            'payment_paypal_client_id',
            'payment_paypal_client_secret',
            'payment_paypal_mode',
            'payment_paypal_status',
        ]);
    }
}
