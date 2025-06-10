<?php

namespace Guestcms\Contact\Http\Requests;

use Guestcms\Contact\Enums\ContactStatusEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class EditContactRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(ContactStatusEnum::values()),
        ];
    }
}
