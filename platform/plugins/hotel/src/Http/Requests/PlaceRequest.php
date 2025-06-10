<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class PlaceRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
