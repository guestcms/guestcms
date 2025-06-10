<?php

namespace Guestcms\PayPal\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class PayPalPaymentCallbackRequest extends Request
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric'],
            'currency' => ['required'],
        ];
    }
}
