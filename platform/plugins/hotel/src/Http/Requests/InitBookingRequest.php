<?php

namespace Guestcms\Hotel\Http\Requests;

use Guestcms\Hotel\Facades\HotelHelper;
use Guestcms\Hotel\Models\Room;
use Guestcms\Support\Http\Requests\Request;

class InitBookingRequest extends Request
{
    public function rules(): array
    {
        $dateFormat = HotelHelper::getDateFormat();

        $rules = [
            'room_id' => ['required', 'exists:ht_rooms,id'],
            'start_date' => ['required', 'string', 'date_format:' . $dateFormat, 'after_or_equal:today'],
            'end_date' => ['required', 'string', 'date_format:' . $dateFormat, 'after_or_equal:start_date'],
            'adults' => [
                'required',
                'integer',
                'min:' . HotelHelper::getMinimumNumberOfGuests(),
                'max:' . HotelHelper::getMaximumNumberOfGuests(),
            ],
            'children' => ['nullable', 'integer', 'min:0'],
            'rooms' => ['nullable', 'integer', 'min:1'],
        ];

        $roomId = $this->input('room_id');

        if ($roomId) {
            $room = Room::query()
                ->select('number_of_rooms')
                ->find($roomId);

            if ($room) {
                $rules['rooms'][] = 'max:' . $room->number_of_rooms;
            }
        }

        return $rules;
    }
}
