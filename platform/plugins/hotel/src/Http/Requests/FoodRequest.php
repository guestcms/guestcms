<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class FoodRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:120'],
            'food_type_id' => ['required', 'exists:ht_food_types,id'],
            'content' => ['nullable', 'string', 'max:100000'],
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
