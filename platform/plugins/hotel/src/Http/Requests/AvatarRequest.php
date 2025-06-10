<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Media\Facades\RvMedia;
use Guestcms\Support\Http\Requests\Request;

class AvatarRequest extends Request
{
    public function rules(): array
    {
        return [
            'avatar_file' => RvMedia::imageValidationRule(),
            'avatar_data' => ['required', 'string'],
        ];
    }
}
