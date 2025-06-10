<?php

namespace Guestcms\Payment\Http\Requests;

use Guestcms\Payment\Enums\PaymentStatusEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(PaymentStatusEnum::values()),
        ];
    }
}
