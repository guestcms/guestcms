<?php

namespace Guestcms\Payment;

use Guestcms\PluginManagement\Abstracts\PluginOperationAbstract;
use Guestcms\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_logs');

        Setting::delete([
            'default_payment_method',
            'payment_cod_status',
            'payment_cod_description',
            'payment_cod_name',
            'payment_cod_fee',
            'payment_cod_fee_type',
            'payment_bank_transfer_status',
            'payment_bank_transfer_description',
            'payment_bank_transfer_name',
            'payment_bank_transfer_fee',
            'payment_bank_transfer_fee_type',
        ]);
    }
}
