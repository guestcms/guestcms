<?php

namespace Guestcms\Shortcode\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class GetShortcodeDataRequest extends Request
{
    public function rules(): array
    {
        return [
            'key' => ['nullable', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:1000000'],
        ];
    }
}
