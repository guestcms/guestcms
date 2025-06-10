<?php

namespace Guestcms\Testimonial\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TestimonialRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:250'],
            'company' => ['nullable', 'string', 'max:250'],
            'content' => ['required', 'string', 'max:1000'],
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
