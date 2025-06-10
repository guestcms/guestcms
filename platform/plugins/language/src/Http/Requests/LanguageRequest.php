<?php

namespace Guestcms\Language\Http\Requests;

use Guestcms\Base\Supports\Language;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LanguageRequest extends Request
{
    public function rules(): array
    {
        return [
            'lang_name' => ['required', 'string', 'max:30', 'min:2'],
            'lang_code' => [
                'required',
                'string',
                Rule::in(Language::getLanguageCodes()),
            ],
            'lang_locale' => [
                'required',
                'string',
                Rule::in(Language::getLocaleKeys()),
            ],
            'lang_flag' => ['required', 'string'],
            'lang_is_rtl' => ['required', 'boolean'],
            'lang_order' => ['required', 'numeric'],
        ];
    }
}
