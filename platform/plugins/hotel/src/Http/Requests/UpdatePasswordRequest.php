<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class UpdatePasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'old_password' => ['required', 'string', 'current_password:customer'],
            'password' => ['required', 'string', 'min:6', 'max:60', 'confirmed'],
        ];
    }
}
