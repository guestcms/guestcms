<?php

namespace Guestcms\Media\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class MediaFolderRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'regex:/^[\pL\s\ \_\-0-9]+$/u'],
            'color' => ['nullable', 'hex_color'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => trans('core/media::media.name_invalid'),
        ];
    }
}
