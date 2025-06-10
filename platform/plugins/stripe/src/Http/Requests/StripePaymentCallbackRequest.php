<?php

namespace Guestcms\Stripe\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class StripePaymentCallbackRequest extends Request
{
    public function rules(): array
    {
        return [
            'session_id' => ['required', 'size:66'],
        ];
    }
}
