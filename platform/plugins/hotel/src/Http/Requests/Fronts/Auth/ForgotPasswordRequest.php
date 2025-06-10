<?php

namespace Guestcms\Hotel\Http\Requests\Fronts\Auth;

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
