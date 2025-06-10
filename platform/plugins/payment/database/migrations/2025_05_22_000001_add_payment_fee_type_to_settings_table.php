<?php

use Guestcms\Payment\Enums\PaymentFeeTypeEnum;
use Guestcms\Payment\Enums\PaymentMethodEnum;
use Guestcms\Setting\Facades\Setting;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        foreach (PaymentMethodEnum::values() as $paymentMethod) {
            Setting::set('payment_' . $paymentMethod . '_fee_type', PaymentFeeTypeEnum::FIXED);
        }

        Setting::save();
    }

    public function down(): void
    {
        foreach (PaymentMethodEnum::values() as $paymentMethod) {
            Setting::delete('payment_' . $paymentMethod . '_fee_type');
        }

        Setting::save();
    }
};
