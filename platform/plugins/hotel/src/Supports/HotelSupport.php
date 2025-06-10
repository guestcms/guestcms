<?php

namespace Guestcms\Hotel\Supports;

use Guestcms\Hotel\Enums\ReviewStatusEnum;
use Guestcms\Hotel\Facades\HotelHelper;
use Guestcms\Theme\Facades\Theme;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class HotelSupport
{
    public function isEnableEmailVerification(): bool
    {
        return (bool) $this->getSetting('verify_customer_email', 0);
    }

    public function getSettingPrefix(): ?string
    {
        return config('plugins.hotel.general.prefix');
    }

    public function isReviewEnabled(): bool
    {
        return (bool) setting('hotel_enable_review_room', 1);
    }

    public function isBookingEnabled(): bool
    {
        return (bool) setting('hotel_enable_booking', true);
    }

    public function getReviewExtraData(): array
    {
        if (! $this->isReviewEnabled()) {
            return [];
        }

        return [
            'withCount' => [
                'reviews' => function ($query): void {
                    $query->where('status', ReviewStatusEnum::APPROVED);
                },
            ],
            'withAvg' => ['reviews', 'star'],
        ];
    }

    public function getSetting(string $key, bool|int|string|null $default = ''): array|int|string|null
    {
        return setting($this->getSettingPrefix() . $key, $default);
    }

    public function loadCountriesStatesCitiesFromPluginLocation(): bool
    {
        if (! is_plugin_active('location')) {
            return false;
        }

        return (bool) $this->getSetting('load_countries_states_cities_from_location_plugin', 0);
    }

    public function viewPath(string $view): string
    {
        $themeView = Theme::getThemeNamespace() . '::views.hotel.' . $view;

        if (view()->exists($themeView)) {
            return $themeView;
        }

        return 'plugins/hotel::themes.' . $view;
    }

    public function getRoomFilters(Request|array $request): array
    {
        if ($request instanceof Request) {
            $request = $request->input();
        }

        $data = [
            'keyword' => Arr::get($request, 'q'),
            'start_date' => Arr::get($request, 'start_date'),
            'end_date' => Arr::get($request, 'end_date'),
            'adults' => Arr::get($request, 'adults', $this->getMinimumNumberOfGuests()),
            'children' => Arr::get($request, 'children', 0),
            'rooms' => Arr::get($request, 'rooms', 1),
            'page' => Arr::get($request, 'page', 1),
            'per_page' => Arr::get($request, 'per_page', 10),
            'room_category_id' => Arr::get($request, 'room_category_id'),
            'min_price' => Arr::get($request, 'min_price'),
            'max_price' => Arr::get($request, 'max_price'),
            'number_of_beds' => Arr::get($request, 'number_of_beds'),
            'min_size' => Arr::get($request, 'min_size'),
            'max_size' => Arr::get($request, 'max_size'),
            'amenities' => Arr::get($request, 'amenities'),
            'is_featured' => Arr::get($request, 'is_featured'),
            'sort_by' => Arr::get($request, 'sort_by'),
            'sort_direction' => Arr::get($request, 'sort_direction', 'asc'),
        ];

        $dateFormat = HotelHelper::getDateFormat();

        try {
            $validator = Validator::make($data, [
                'q' => ['nullable', 'string'],
                'keyword' => ['nullable', 'string'],
                'adults' => [
                    'nullable',
                    'int',
                    'min:' . $this->getMinimumNumberOfGuests(),
                    'max:' . $this->getMaximumNumberOfGuests(),
                ],
                'children' => ['nullable', 'int', 'min:0'],
                'rooms' => ['nullable', 'int', 'min:1'],
                'page' => ['nullable', 'int', 'min:1'],
                'per_page' => ['nullable', 'int', 'min:1'],
                'room_category_id' => ['nullable', 'int', 'exists:ht_room_categories,id'],
                'start_date' => ['nullable', 'string', 'date', 'date_format:' . $dateFormat, 'after_or_equal:today'],
                'end_date' => ['nullable', 'string', 'date', 'date_format:' . $dateFormat, 'after_or_equal:start_date'],
                'room_id' => ['nullable', 'integer', 'exists:hotel_rooms,id'],
                'min_price' => ['nullable', 'numeric', 'min:0'],
                'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
                'number_of_beds' => ['nullable', 'integer', 'min:1'],
                'min_size' => ['nullable', 'numeric', 'min:0'],
                'max_size' => ['nullable', 'numeric', 'min:0', 'gte:min_size'],
                'amenities' => ['nullable', 'array'],
                'amenities.*' => ['integer', 'exists:ht_amenities,id'],
                'is_featured' => ['nullable', 'boolean'],
                'sort_by' => ['nullable', 'string', 'in:price,name,created_at,number_of_beds,size'],
                'sort_direction' => ['nullable', 'string', 'in:asc,desc'],
            ]);

            return $validator->valid();
        } catch (Throwable) {
            return [];
        }
    }

    public function getRoomBookingParams(): array
    {
        $request = request();

        try {
            if ($request->input('start_date') && $request->input('end_date')) {
                $startDate = $this->dateFromRequest($request->input('start_date'));
                $endDate = $this->dateFromRequest($request->input('end_date'));
            } else {
                $startDate = Carbon::now();
                $endDate = Carbon::now()->addDay();
            }
        } catch (Throwable) {
            $startDate = Carbon::now();
            $endDate = Carbon::now()->addDay();
        }

        $adults = $request->input('adults', $this->getMinimumNumberOfGuests());
        $children = $request->input('children', 0);
        $rooms = $request->input('rooms', 1);

        $nights = $endDate->diffInDays($startDate);

        return [
            $startDate,
            $endDate,
            $adults,
            $nights,
            $children,
            $rooms,
        ];
    }

    public function getCheckoutData(?string $key = null): mixed
    {
        $checkoutToken = session('checkout_token');

        if (! $checkoutToken) {
            $checkoutToken = Str::upper(Str::random(32));
        }

        $sessionData = [];
        if (session()->has($checkoutToken)) {
            $sessionData = session($checkoutToken);
        }

        if ($key) {
            return $sessionData[$key] ?? null;
        }

        return $sessionData;
    }

    public function saveCheckoutData(array $data): void
    {
        $checkoutToken = session('checkout_token');

        $sessionData = $this->getCheckoutData();

        $data = array_merge($sessionData, $data);

        session()->put($checkoutToken, $data);
    }

    public function getDateFormat(): string
    {
        return (setting('hotel_booking_date_format') ?: config('plugins.hotel.hotel.date_format')) ?: 'd-m-Y';
    }

    public function getBookingFormDateFormat(): string
    {
        return ($this->getDateFormatDatepicker() ?: config('plugins.hotel.hotel.booking_form_date_format')) ?: 'dd-mm-yyyy';
    }

    public function dateFromRequest(string $date): Carbon|false
    {
        return Carbon::createFromFormat($this->getDateFormat(), $date);
    }

    public function getDateRangeInReport(Request $request): array
    {
        $startDate = Carbon::now()->subDays(29);
        $endDate = Carbon::now();

        if ($request->input('date_from')) {
            try {
                $startDate = Carbon::now()->createFromFormat('Y-m-d', $request->input('date_from'));
            } catch (Exception) {
                $startDate = Carbon::now()->subDays(29);
            }
        }

        if ($request->input('date_to')) {
            try {
                $endDate = Carbon::now()->createFromFormat('Y-m-d', $request->input('date_to'));
            } catch (Exception) {
                $endDate = Carbon::now();
            }
        }

        if ($endDate->gt(Carbon::now())) {
            $endDate = Carbon::now();
        }

        if ($startDate->gt($endDate)) {
            $startDate = Carbon::now()->subDays(29);
        }

        $predefinedRange = $request->input('predefined_range', trans('plugins/hotel::booking-report.ranges.last_30_days'));

        return [$startDate, $endDate, $predefinedRange];
    }

    public function getMinimumNumberOfGuests(): int
    {
        return (int) setting('hotel_minimum_number_of_guests', 1);
    }

    public function getMaximumNumberOfGuests(): int
    {
        return (int) setting('hotel_maximum_number_of_guests', 10);
    }

    public function getBookingNumber(string|int $id): string
    {
        $prefix = setting('hotel_booking_number_prefix') ? setting('hotel_booking_number_prefix') . '-' : '';
        $suffix = setting('hotel_booking_number_suffix') ? '-' . setting('hotel_booking_number_suffix') : '';

        return sprintf(
            '#%s%d%s',
            $prefix,
            (int) config('plugins.hotel.hotel.default_number_start_number') + $id,
            $suffix
        );
    }

    public function getBookingDateFormatTemplates(): array
    {
        return [
            [
                'carbon' => 'd-m-Y',
                'datepicker' => 'dd-mm-yyyy',
            ],
            [
                'carbon' => 'm-d-Y',
                'datepicker' => 'mm-dd-yyyy',
            ],
            [
                'carbon' => 'Y-m-d',
                'datepicker' => 'yyyy-mm-dd',
            ],
            [
                'carbon' => 'd/m/Y',
                'datepicker' => 'dd/mm/yyyy',
            ],
            [
                'carbon' => 'm/d/Y',
                'datepicker' => 'mm/dd/yyyy',
            ],
            [
                'carbon' => 'Y/m/d',
                'datepicker' => 'yyyy/mm/dd',
            ],
        ];
    }

    public function getBookingDateFormatOptions(): array
    {
        $templates = $this->getBookingDateFormatTemplates();

        $options = [];

        $now = Carbon::now();

        $options[''] = trans('plugins/hotel::settings.general.default_system_date_format');

        foreach ($templates as $template) {
            $options[$template['carbon']] = $template['carbon'] . ' (' . $now->format($template['carbon']) . ')';
        }

        return $options;
    }

    public function getDateFormatDatepicker(): ?string
    {
        $dateFormat = $this->getDateFormat();
        $templates = $this->getBookingDateFormatTemplates();

        foreach ($templates as $template) {
            if ($template['carbon'] == $dateFormat) {
                return $template['datepicker'];
            }
        }

        return null;
    }

    public function isEnableFoodOrder(): bool
    {
        return (bool) $this->getSetting('hotel_booking_enabled_food_order', false);
    }
}
