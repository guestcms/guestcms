<section class="booking-form boxed-layout">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-11">
                <div class="booking-form-inner">
                    <form action="{{ route('public.rooms') }}">
                        <div class="row align-items-end">
                            <div class="col-lg-3 col-md-6">
                                <div class="inputs-filed mt-30">
                                    <label for="arrival-date">{{ __('Check-In') }}</label>
                                    <div class="icon"><i class="fal fa-calendar-alt"></i></div>
                                    <input
                                        id="arrival-date"
                                        name="start_date"
                                        type="text"
                                        data-date-format="{{ HotelHelper::getBookingFormDateFormat() }}"
                                        placeholder="{{ Carbon\Carbon::now()->format(HotelHelper::getDateFormat()) }}"
                                        data-locale="{{ App::getLocale() }}"
                                        value="{{ old('start_date', Carbon\Carbon::now()->format(HotelHelper::getDateFormat())) }}"
                                        class="date-picker"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="inputs-filed mt-30">
                                    <label for="departure-date">{{ __('Check-Out') }}</label>
                                    <div class="icon"><i class="fal fa-calendar-alt"></i></div>
                                    <input
                                        id="departure-date"
                                        name="end_date"
                                        type="text"
                                        data-date-format="{{ HotelHelper::getBookingFormDateFormat() }}"
                                        placeholder="{{ Carbon\Carbon::now()->addDay()->format(HotelHelper::getDateFormat()) }}"
                                        data-locale="{{ App::getLocale() }}"
                                        value="{{ old('end_date', Carbon\Carbon::now()->addDay()->format(HotelHelper::getDateFormat())) }}"
                                        class="date-picker"
                                    >
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="inputs-filed mt-30">
                                    <label for="guests_and_rooms">{{ __('Guests and Rooms') }}</label>
                                    <div class="icon"><i class="fal fa-user"></i></div>
                                    <button data-bb-toggle="toggle-guests-and-rooms" class="text-truncate" type="button" data-target="#toggle-guests-and-rooms">
                                        <span data-bb-toggle="filter-adults-count" class="mr-1">1</span> {{ __('Adult(s)') }},
                                        <span data-bb-toggle="filter-children-count" class="mr-1">0</span> {{ __('Child(ren)') }},
                                        <span data-bb-toggle="filter-rooms-count" class="mr-1">1</span> {{ __('Room(s)') }}
                                    </button>
                                    <div class="custom-dropdown dropdown-menu p-3" id="toggle-guests-and-rooms">
                                        <div class="inputs-filed">
                                            <label for="adults">{{ __('Adults') }}</label>
                                            <div class="input-quantity">
                                                <button type="button" class="main-btn" data-bb-toggle="decrement-room">-</button>
                                                <input type="number" id="adults" name="adults" readonly value="1" min="{{ HotelHelper::getMinimumNumberOfGuests() }}" max="{{ HotelHelper::getMaximumNumberOfGuests() }}">
                                                <button type="button" class="main-btn" data-bb-toggle="increment-room">+</button>
                                            </div>
                                        </div>
                                        <div class="inputs-filed mt-30">
                                            <label for="children">{{ __('Children') }}</label>
                                            <div class="input-quantity">
                                                <button type="button" class="main-btn" data-bb-toggle="decrement-room">-</button>
                                                <input type="number" id="children" name="children" readonly value="0" min="0" max="{{ HotelHelper::getMaximumNumberOfGuests() }}">
                                                <button type="button" class="main-btn" data-bb-toggle="increment-room">+</button>
                                            </div>
                                        </div>
                                        <div class="inputs-filed mt-30">
                                            <label for="rooms">{{ __('Rooms') }}</label>
                                            <div class="input-quantity">
                                                <button type="button" class="main-btn" data-bb-toggle="decrement-room">-</button>
                                                <input type="number" id="rooms" name="rooms" readonly value="1" min="1" max="{{ 10 }}">
                                                <button type="button" class="main-btn" data-bb-toggle="increment-room">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="inputs-filed mt-30">
                                    <button type="submit">{{ __('Check Availability') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
