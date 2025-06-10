<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Rules\MediaImageRule;
use Guestcms\Hotel\Enums\ServicePriceTypeEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class ServiceRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:400'],
            'content' => ['nullable', 'string', 'max:100000'],
            'image' => ['nullable', 'string', new MediaImageRule()],
            'status' => Rule::in(BaseStatusEnum::values()),
            'price' => ['required', 'numeric', 'min:0'],
            'price_type' => Rule::in(ServicePriceTypeEnum::values()),
        ];
    }
}
