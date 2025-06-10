<?php

namespace Guestcms\Hotel\Forms;

use Guestcms\Base\Forms\FieldOptions\StatusFieldOption;
use Guestcms\Base\Forms\Fields\SelectField;
use Guestcms\Base\Forms\FormAbstract;
use Guestcms\Hotel\Enums\BookingStatusEnum;
use Guestcms\Hotel\Http\Requests\UpdateBookingRequest;
use Guestcms\Hotel\Models\Booking;

class BookingForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->setupModel(new Booking())
            ->setValidatorClass(UpdateBookingRequest::class)
            ->withCustomFields()
            ->add('status', SelectField::class, StatusFieldOption::make()->choices(BookingStatusEnum::labels())->toArray())
            ->setBreakFieldPoint('status')
            ->addMetaBoxes([
                'information' => [
                    'title' => trans('plugins/hotel::booking.booking_information'),
                    'content' => view('plugins/hotel::booking-info', ['booking' => $this->getModel()])->render(),
                    'attributes' => [
                        'style' => 'margin-top: 0',
                    ],
                ],
            ]);
    }
}
