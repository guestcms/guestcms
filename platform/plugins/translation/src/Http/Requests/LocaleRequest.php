<?php

namespace Guestcms\Translation\Http\Requests;

use Guestcms\Base\Supports\Language;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LocaleRequest extends Request
{
    public function rules(): array
    {
        return [
            'locale' => ['required', Rule::in(Language::getLocaleKeys())],
        ];
    }
}
