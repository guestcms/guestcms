<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Hotel\Enums\BookingStatusEnum;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends Request
{
    public function rules(): array
    {
        return [
            'status' => Rule::in(BookingStatusEnum::values()),
        ];
    }
}
