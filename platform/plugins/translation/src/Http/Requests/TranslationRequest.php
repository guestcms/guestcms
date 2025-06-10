<?php

namespace Guestcms\Translation\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class TranslationRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:250'],
        ];
    }
}
