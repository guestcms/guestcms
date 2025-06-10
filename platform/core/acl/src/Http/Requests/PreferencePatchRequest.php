<?php

namespace Guestcms\ACL\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class PreferencePatchRequest extends Request
{
    public function rules(): array
    {
        return [
            'minimal_sidebar' => ['sometimes', 'required', 'in:yes,no'],
        ];
    }
}
