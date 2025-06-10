<?php

namespace Guestcms\Hotel\Http\Controllers;

use Guestcms\Base\Http\Actions\DeleteResourceAction;
use Guestcms\Base\Http\Controllers\BaseController;
use Guestcms\Hotel\Events\BookingStatusChanged;
use Guestcms\Hotel\Events\BookingUpdated;
use Guestcms\Hotel\Forms\BookingForm;
use Guestcms\Hotel\Http\Requests\UpdateBookingRequest;
use Guestcms\Hotel\Models\Booking;
use Guestcms\Hotel\Tables\BookingTable;

class BookingController extends BaseController
{
    public function __construct()
    {
        $this
            ->breadcrumb()
            ->add(trans('plugins/hotel::booking.name'), route('booking.index'));
    }

    public function index(BookingTable $table)
    {
        $this->pageTitle(trans('plugins/hotel::booking.name'));

        return $table->renderTable();
    }

    public function edit(Booking $booking)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $booking->room->room_name]));

        return BookingForm::createFromModel($booking)->renderForm();
    }

    public function update(Booking $booking, UpdateBookingRequest $request)
    {
        $status = $booking->status;

        BookingForm::createFromModel($booking)
            ->setRequest($request)
            ->save();

        BookingUpdated::dispatch($booking);

        if ($booking->status != $status) {
            BookingStatusChanged::dispatch($status, $booking);
        }

        return $this
            ->httpResponse()
            ->setPreviousUrl(route('booking.index'))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Booking $booking)
    {
        return DeleteResourceAction::make($booking);
    }
}
