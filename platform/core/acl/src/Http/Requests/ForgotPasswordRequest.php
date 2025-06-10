<?php

namespace Guestcms\ACL\Http\Requests;

use Guestcms\Base\Rules\EmailRule;
use Guestcms\Support\Http\Requests\Request;

class ForgotPasswordRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', new EmailRule()],
        ];
    }
}
