<?php

namespace Guestcms\ACL\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class LoginRequest extends Request
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:4', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'max:60'],
        ];
    }
}
