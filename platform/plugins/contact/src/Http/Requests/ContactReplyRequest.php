<?php

namespace Guestcms\Contact\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class ContactReplyRequest extends Request
{
    public function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:10000'],
        ];
    }
}
