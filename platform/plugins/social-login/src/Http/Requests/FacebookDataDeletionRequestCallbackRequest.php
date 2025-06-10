<?php

namespace Guestcms\SocialLogin\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class FacebookDataDeletionRequestCallbackRequest extends Request
{
    public function rules(): array
    {
        return [
            'signed_request' => ['required', 'string'],
        ];
    }
}
