<?php

namespace Guestcms\LanguageAdvanced\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class LanguageAdvancedRequest extends Request
{
    public function rules(): array
    {
        return [
            'model' => ['required', 'string', 'max:255'],
        ];
    }
}
