<?php

namespace Guestcms\Hotel\Http\Requests\Settings;

use Guestcms\Support\Http\Requests\Request;

class InvoiceTemplateSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:1000000'],
        ];
    }
}
