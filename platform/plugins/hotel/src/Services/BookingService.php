<?php

namespace Guestcms\Hotel\Services;

use Guestcms\Hotel\Enums\BookingStatusEnum;
use Guestcms\Hotel\Events\BookingCreated;
use Guestcms\Hotel\Models\Booking;
use Guestcms\Payment\Enums\PaymentStatusEnum;
use Guestcms\Payment\Models\Payment;

class BookingService
{
    public function processBooking(int $bookingId, ?string $chargeId = null): ?Booking
    {
        /**
         * @var Booking $booking
         */
        $booking = Booking::query()->find($bookingId);

        if (! $booking) {
            return null;
        }

        if ($chargeId && is_plugin_active('payment')) {
            $payment = Payment::query()->where(['charge_id' => $chargeId])->first();

            if ($payment) {
                $booking->payment_id = $payment->getKey();

                if ($payment->status == PaymentStatusEnum::COMPLETED) {
                    $booking->status = BookingStatusEnum::PROCESSING;
                }

                $booking->save();
            }
        }

        BookingCreated::dispatch($booking);

        return $booking;
    }
}
