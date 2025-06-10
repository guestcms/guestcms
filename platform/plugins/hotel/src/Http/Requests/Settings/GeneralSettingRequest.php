<?php

namespace Guestcms\Hotel\Http\Requests\Settings;

use Guestcms\Base\Rules\OnOffRule;
use Guestcms\Hotel\Facades\HotelHelper;
use Guestcms\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class GeneralSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'hotel_enable_booking' => $onOffRule = [new OnOffRule()],
            'hotel_minimum_number_of_guests' => ['nullable', 'integer', 'min:1', 'lt:hotel_maximum_number_of_guests'],
            'hotel_maximum_number_of_guests' => ['nullable', 'integer', 'min:1', 'gt:hotel_minimum_number_of_guests'],
            'hotel_booking_number_prefix' => ['nullable', 'string', 'max:120'],
            'hotel_booking_number_suffix' => ['nullable', 'string', 'max:120'],
            'hotel_booking_date_format' => ['nullable', 'string', Rule::in(array_keys(HotelHelper::getBookingDateFormatOptions()))],
            'hotel_booking_enabled_food_order' => $onOffRule,
        ];
    }
}
