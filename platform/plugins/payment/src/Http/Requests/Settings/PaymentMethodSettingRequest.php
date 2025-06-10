<?php

namespace Guestcms\Payment\Http\Requests\Settings;

use Guestcms\Payment\Enums\PaymentMethodEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PaymentMethodSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'default_payment_method' => ['required', Rule::in(PaymentMethodEnum::values())],
        ];
    }
}
