<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Support\Http\Requests\Request;

class CustomerCreateRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:60', 'min:2'],
            'last_name' => ['required', 'string', 'max:60', 'min:2'],
            'email' => ['required', 'max:60', 'min:6', 'email', 'unique:ht_customers'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['nullable', 'string', ...explode('|', BaseHelper::getPhoneValidationRule())],
        ];
    }
}
