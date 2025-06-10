<?php

namespace Guestcms\Hotel\Forms\Settings;

use Guestcms\Base\Forms\FieldOptions\CheckboxFieldOption;
use Guestcms\Base\Forms\FieldOptions\LabelFieldOption;
use Guestcms\Base\Forms\FieldOptions\NumberFieldOption;
use Guestcms\Base\Forms\FieldOptions\OnOffFieldOption;
use Guestcms\Base\Forms\FieldOptions\SelectFieldOption;
use Guestcms\Base\Forms\FieldOptions\TextFieldOption;
use Guestcms\Base\Forms\Fields\LabelField;
use Guestcms\Base\Forms\Fields\NumberField;
use Guestcms\Base\Forms\Fields\OnOffCheckboxField;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\Fields\TextField;
use Guestcms\Hotel\Facades\HotelHelper;
use Guestcms\Hotel\Http\Requests\Settings\GeneralSettingRequest;
use Guestcms\Setting\Forms\SettingForm;

class GeneralSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/hotel::settings.general.title'))
            ->setSectionDescription(trans('plugins/hotel::settings.general.description'))
            ->setValidatorClass(GeneralSettingRequest::class)
            ->add(
                'hotel_enable_booking',
                OnOffCheckboxField::class,
                CheckboxFieldOption::make()
                    ->value($enabled = HotelHelper::isBookingEnabled())
                    ->label(trans('plugins/hotel::settings.general.enable_booking'))
            )
            ->addOpenCollapsible('hotel_enable_booking', '1', $enabled)
            ->tap(function (GeneralSettingForm $form) {
                $minimumNumberOfGuests = HotelHelper::getMinimumNumberOfGuests();
                $maximumNumberOfGuests = HotelHelper::getMaximumNumberOfGuests();

                return $form
                    ->add(
                        'hotel_booking_enabled_food_order',
                        OnOffCheckboxField::class,
                        OnOffFieldOption::make()
                            ->value(HotelHelper::isEnableFoodOrder())
                            ->label(trans('plugins/hotel::settings.general.enable_food_order'))
                    )
                    ->add(
                        'hotel_minimum_number_of_guests',
                        NumberField::class,
                        NumberFieldOption::make()
                            ->attributes([
                                'min' => 1,
                                'max' => old('hotel_maximum_number_of_guests', $maximumNumberOfGuests),
                            ])
                            ->value($minimumNumberOfGuests)
                            ->label(trans('plugins/hotel::settings.general.minimum_number_of_guests'))
                    )
                    ->add(
                        'hotel_maximum_number_of_guests',
                        NumberField::class,
                        NumberFieldOption::make()
                            ->attributes([
                                'min' => old('hotel_minimum_number_of_guests', $minimumNumberOfGuests),
                            ])
                            ->value($maximumNumberOfGuests)
                            ->label(trans('plugins/hotel::settings.general.maximum_number_of_guests'))
                    );
            })
            ->addCloseCollapsible('hotel_enable_booking', '1')
            ->add(
                'booking_number_format_section',
                LabelField::class,
                LabelFieldOption::make()
                    ->wrapperAttributes(['class' => 'mb-0'])
                    ->label(trans('plugins/hotel::settings.general.booking_number_format.title'))
            )
            ->add('booking_number_format_description', 'html', [
                'html' => sprintf(
                    '<p class="text-muted small">%s</p>',
                    trans('plugins/hotel::settings.general.booking_number_format.description', ['format' => sprintf(
                        '<span class="sample-booking-number-prefix">%s</span>%s' .
                        '<span class="sample-booking-number-suffix">%s</span>',
                        setting('hotel_booking_number_prefix') ? setting('hotel_booking_number_prefix') . '-' : '',
                        config('plugins.hotel.hotel.default_number_start_number'),
                        setting('hotel_booking_number_suffix') ? '-' . setting('hotel_booking_number_suffix') : '',
                    )])
                ),
            ])
            ->addOpenFieldset('booking_number_format_section', ['class' => 'form-fieldset d-flex gap-3'])
            ->add(
                'hotel_booking_number_prefix',
                TextField::class,
                TextFieldOption::make()
                    ->wrapperAttributes(['class' => 'position-relative w-full'])
                    ->label(trans('plugins/hotel::settings.general.booking_number_format.start_with'))
                    ->value(setting('hotel_booking_number_prefix'))
            )
            ->add(
                'hotel_booking_number_suffix',
                TextField::class,
                TextFieldOption::make()
                    ->wrapperAttributes(['class' => 'position-relative w-full'])
                    ->label(trans('plugins/hotel::settings.general.booking_number_format.end_with'))
                    ->value(setting('hotel_booking_number_suffix'))
            )
            ->addCloseFieldset('booking_number_format_section')
            ->add(
                'hotel_booking_date_format',
                SelectField::class,
                SelectFieldOption::make()
                    ->choices(HotelHelper::getBookingDateFormatOptions())
                    ->selected(setting('hotel_booking_date_format'))
                    ->label(trans('plugins/hotel::settings.general.booking_date_format'))
            );
    }
}
