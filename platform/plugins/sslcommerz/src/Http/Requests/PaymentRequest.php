<?php

namespace Guestcms\SslCommerz\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class PaymentRequest extends Request
{
    public function rules(): array
    {
        return [
            'tran_id' => ['required'],
            'amount' => ['required'],
            'currency' => ['required'],
            'value_a' => ['required'],
            'value_b' => ['required'],
        ];
    }
}
