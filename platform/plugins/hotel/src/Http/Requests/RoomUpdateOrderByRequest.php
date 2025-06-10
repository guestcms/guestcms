<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Support\Http\Requests\Request;

class RoomUpdateOrderByRequest extends Request
{
    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric'],
        ];
    }
}
