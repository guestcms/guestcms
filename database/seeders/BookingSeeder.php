<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Hotel\Enums\BookingStatusEnum;
use Guestcms\Hotel\Facades\InvoiceHelper;
use Guestcms\Hotel\Models\Booking;
use Guestcms\Hotel\Models\BookingAddress;
use Guestcms\Hotel\Models\BookingRoom;
use Guestcms\Hotel\Models\Customer;
use Guestcms\Hotel\Models\Room;
use Guestcms\Hotel\Models\Service;
use Guestcms\Payment\Enums\PaymentMethodEnum;
use Guestcms\Payment\Enums\PaymentStatusEnum;
use Guestcms\Payment\Models\Payment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BookingSeeder extends BaseSeeder
{
    public function run(): void
    {
        Booking::query()->truncate();
        BookingRoom::query()->truncate();
        BookingAddress::query()->truncate();
        Payment::query()->truncate();

        $rooms = Room::query()->get();
        $customers = Customer::query()->get();
        $services = Service::query()->get();

        $period = CarbonPeriod::between(
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        );

        $faker = $this->fake();

        foreach ($period as $dateTime) {
            $room = $rooms->random();
            $customer = $customers->random();
            $randomServices = $faker->randomElement($services);

            $address = new BookingAddress([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'phone' => $faker->phoneNumber(),
                'email' => $faker->safeEmail(),
                'country' => $faker->country(),
                'state' => $faker->city(),
                'city' => $faker->city(),
                'address' => $faker->address(),
                'zip' => $faker->postcode(),
            ]);

            $room = new BookingRoom([
                'room_id' => $room->id,
                'room_name' => $room->name,
                'room_image' => Arr::first($room->images),
                'price' => $price = $room->price,
                'number_of_rooms' => $qty = rand(1, 3),
                'start_date' => $dateTime->toDateString(),
                'end_date' => $dateTime->clone()->addDays(rand(1, 3))->toDateString(),
            ]);

            $amount = $price * $qty;

            $booking = Booking::query()->create([
                'customer_id' => $customer->id,
                'amount' => $amount,
                'sub_total' => $amount,
                'requests' => $faker->sentence(),
                'number_of_guests' => $qty * rand(1, 3),
                'transaction_id' => Str::random(20),
                'tax_amount' => 0,
                'status' => $faker->randomElement(BookingStatusEnum::values()),
                'booking_number' => (new Booking())->generateUniqueBookingNumber(),
            ]);

            $booking->services()->sync($randomServices);

            $address->booking()->associate($booking)->save();

            $room->booking()->associate($booking)->save();

            $payment = Payment::query()->create([
                'amount' => $booking->amount,
                'currency' => 'USD',
                'user_id' => $customer->id,
                'charge_id' => Str::random(20),
                'payment_channel' => $faker->randomElement(PaymentMethodEnum::values()),
                'status' => $faker->randomElement(PaymentStatusEnum::values()),
                'order_id' => $booking->getKey(),
                'payment_type' => 'direct',
                'customer_id' => $customer->id,
                'customer_type' => $customer::class,
            ]);

            $booking->payment()->associate($payment)->save();

            InvoiceHelper::store($booking);
        }
    }
}
