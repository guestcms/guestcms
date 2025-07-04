<?php

namespace Guestcms\Table\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class BulkChangeRequest extends Request
{
    public function rules(): array
    {
        return [
            'key' => ['required', 'string'],
            'class' => ['required', 'string'],
        ];
    }
}
