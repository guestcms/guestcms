<div class="room-booking-form">
    @if (!$availableForBooking)
        <h5 class="title">{{ __('Check Availability') }}</h5>
    @endif
    <form action="{{ $availableForBooking ? route('public.booking') : route('public.rooms') }}" method="{{ $availableForBooking ? 'POST' : 'GET' }}">
        @if ($availableForBooking)
            @csrf
            @if ($room)
                <input type="hidden" name="room_id" value="{{ $room->id }}">
            @endif
        @endif
        <div class="input-group input-group-two left-icon mb-20">
            <label for="arrival-date">{{ __('Check-In') }}</label>
            <div class="icon"><i class="fal fa-calendar-alt"></i></div>
            <input
                type="text"
                data-date-format="{{ HotelHelper::getBookingFormDateFormat() }}"
                placeholder="{{ request()->query('start_date', Carbon\Carbon::now()->format(HotelHelper::getDateFormat())) }}"
                data-locale="{{ App::getLocale() }}"
                value="{{ request()->query('start_date', Carbon\Carbon::now()->format(HotelHelper::getDateFormat())) }}"
                name="start_date"
                id="arrival-date"
                class="date-picker"
            >
        </div>
        <div class="input-group input-group-two left-icon mb-20">
            <label for="departure-date">{{ __('Check-Out') }}</label>
            <div class="icon"><i class="fal fa-calendar-alt"></i></div>
            <input
                type="text"
                data-date-format="{{ HotelHelper::getBookingFormDateFormat() }}"
                placeholder="{{ request()->query('end_date', Carbon\Carbon::now()->addDay()->format(HotelHelper::getDateFormat())) }}"
                data-locale="{{ App::getLocale() }}"
                value="{{ request()->query('end_date', Carbon\Carbon::now()->addDay()->format(HotelHelper::getDateFormat())) }}"
                name="end_date"
                id="departure-date"
                class="date-picker"
            >
        </div>
        <div class="input-group input-group-two left-icon mb-20">
            <label for="adults">{{ __('Adults') }}</label>
            <div class="input-quantity">
                <button type="button" class="main-btn" data-bb-toggle="decrement-room">-</button>
                <input type="number" id="adults" name="adults" readonly value="{{ BaseHelper::stringify(request()->integer('adults', 1)) }}" min="{{ HotelHelper::getMinimumNumberOfGuests() }}" max="{{ $availableForBooking ? $room->max_adults : HotelHelper::getMaximumNumberOfGuests() }}">
                <button type="button" class="main-btn" data-bb-toggle="increment-room">+</button>
            </div>
        </div>
        <div class="input-group input-group-two left-icon mb-20">
            <label for="children">{{ __('Children') }}</label>
            <div class="input-quantity">
                <button type="button" class="main-btn" data-bb-toggle="decrement-room">-</button>
                <input type="number" id="children" name="children" readonly value="{{ BaseHelper::stringify(request()->integer('children')) ?: 0 }}" min="0" max="{{ $availableForBooking ? $room->max_children : HotelHelper::getMaximumNumberOfGuests() }}">
                <button type="button" class="main-btn" data-bb-toggle="increment-room">+</button>
            </div>
        </div>
        <div class="input-group input-group-two left-icon mb-20">
            <label for="rooms">{{ __('Rooms') }}</label>
            <div class="input-quantity">
                <button type="button" class="main-btn" data-bb-toggle="decrement-room">-</button>
                <input type="number" id="rooms" name="rooms" readonly value="{{ BaseHelper::stringify(request()->integer('rooms', 1)) }}" min="1" max="{{ $availableForBooking ? $room->number_of_rooms : 10 }}">
                <button type="button" class="main-btn" data-bb-toggle="increment-room">+</button>
            </div>
        </div>
        <div class="input-group">
            <button class="main-btn btn-filled">{{ $availableForBooking ? __('Book Now') : __('Check Availability') }}</button>
        </div>
    </form>
</div>
