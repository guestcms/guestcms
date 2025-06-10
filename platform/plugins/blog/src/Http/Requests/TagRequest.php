<?php

namespace Guestcms\Blog\Http\Requests;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TagRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:400'],
            'status' => [Rule::in(BaseStatusEnum::values())],
        ];
    }
}
