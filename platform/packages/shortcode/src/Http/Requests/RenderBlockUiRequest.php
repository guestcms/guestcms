<?php

namespace Guestcms\Shortcode\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class RenderBlockUiRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'attributes' => ['nullable', 'array'],
            'attributes.*' => ['nullable', 'string'],
        ];
    }
}
