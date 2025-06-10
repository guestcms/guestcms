<?php

namespace Guestcms\Payment\Http\Requests;

use Guestcms\Payment\Enums\PaymentMethodEnum;
use Guestcms\Payment\Supports\PaymentHelper;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PaymentMethodRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $key = get_payment_setting_key('available_countries', $this->input('type'));

        $this->merge([
            $key => $this->input($key, []),
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'string', 'max:120', Rule::in(PaymentMethodEnum::values())],
            ...PaymentHelper::getPaymentMethodRules($this->input('type')),
        ];
    }
}
