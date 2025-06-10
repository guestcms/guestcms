<?php

namespace Guestcms\Slug\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class SlugRequest extends Request
{
    public function rules(): array
    {
        return [
            'value' => ['required', 'string'],
            'slug_id' => ['required', 'string'],
            'model' => ['nullable', 'string'],
        ];
    }
}
