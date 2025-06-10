<?php

namespace Guestcms\Base\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class SelectSearchAjaxRequest extends Request
{
    public function rules(): array
    {
        return [
            'search' => ['required', 'string'],
            'page' => ['required', 'integer'],
        ];
    }
}
