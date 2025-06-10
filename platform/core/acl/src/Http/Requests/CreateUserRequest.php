<?php

namespace Guestcms\ACL\Http\Requests;

use Guestcms\ACL\Models\User;
use Guestcms\Base\Rules\EmailRule;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CreateUserRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:60', 'min:2'],
            'last_name' => ['required', 'string', 'max:60', 'min:2'],
            'email' => [
                'required',
                'min:6',
                'max:60',
                new EmailRule(),
                Rule::unique((new User())->getTable(), 'email'),
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'username' => [
                'required',
                'string',
                'alpha_dash',
                'min:4',
                'max:30',
                Rule::unique((new User())->getTable(), 'username'),
            ],
        ];
    }
}
