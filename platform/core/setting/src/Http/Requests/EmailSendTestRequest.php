<?php

namespace Guestcms\Setting\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class EmailSendTestRequest extends Request
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }
}
