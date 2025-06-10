<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Hotel\Enums\CouponTypeEnum;
use Guestcms\Hotel\Models\Coupon;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CouponRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'quantity' => $this->has('is_unlimited') ? null : $this->input('quantity'),
        ]);
    }

    public function rules(): array
    {
        $valueRules = [
            'required',
            'numeric',
            'min:1',
        ];

        if ($this->input('type') === CouponTypeEnum::PERCENTAGE) {
            $valueRules[] = 'max:100';
        }

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique(Coupon::class, 'code')->ignore($this->route('coupon')),
            ],
            'type' => ['required', 'string', Rule::in(CouponTypeEnum::values())],
            'value' => $valueRules,
            'quantity' => ['nullable', 'numeric', 'min:1'],
            'expires_date' => ['nullable', 'date_format:' . BaseHelper::getDateFormat()],
            'expires_time' => ['nullable', 'date_format:G:i'],
        ];
    }
}
