<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Hotel\Facades\HotelHelper;
use Guestcms\Support\Http\Requests\Request;

class CalculateBookingAmountRequest extends Request
{
    public function rules(): array
    {
        $dateFormat = HotelHelper::getDateFormat();

        return [
            'room_id' => ['required', 'exists:ht_rooms,id'],
            'start_date' => 'date|required:date_format:' . $dateFormat,
            'end_date' => 'date|required:date_format:' . $dateFormat,
            'services' => ['nullable', 'array'],
        ];
    }
}
