<?php

namespace Guestcms\Setting\Http\Requests;

use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Support\Http\Requests\Request;

class EmailTemplateChangeStatusRequest extends Request
{
    public function rules(): array
    {
        return [
            'key' => ['required', 'string'],
            'value' => [new OnOffRule()],
        ];
    }
}
