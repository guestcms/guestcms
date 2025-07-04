<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Support\Http\Requests\Request;

class EditAccountRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:60', 'min:2'],
            'last_name' => ['required', 'string', 'max:60', 'min:2'],
            'phone' => 'sometimes|' . BaseHelper::getPhoneValidationRule(),
            'dob' => ['nullable', 'date', 'max:20', 'sometimes'],
            'zip' => ['nullable', 'string', 'max:10'],
        ];
    }
}
