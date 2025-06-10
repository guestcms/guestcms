<?php

namespace Guestcms\ACL\Http\Requests;

use Guestcms\Base\Rules\EmailRule;
use Guestcms\Support\Http\Requests\Request;

class ResetPasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', new EmailRule()],
            'password' => ['required', 'confirmed', 'min:6'],
        ];
    }
}
