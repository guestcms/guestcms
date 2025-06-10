<?php

namespace Guestcms\Media\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class MediaListRequest extends Request
{
    public function rules(): array
    {
        return [
            'folder_id' => ['nullable', 'string'],
        ];
    }
}
