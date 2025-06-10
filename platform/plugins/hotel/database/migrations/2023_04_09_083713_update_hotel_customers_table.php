<?php

use Guestcms\Hotel\Models\BookingAddress;
use Guestcms\Hotel\Models\Customer;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration
{
    public function up(): void
    {
        $bookingAddresses = BookingAddress::query()
            ->select([
                'first_name',
                'last_name',
                'email',
                'phone',
                'created_at',
                'updated_at',
            ])
            ->distinct('email')
            ->get();

        if ($bookingAddresses->count()) {
            Customer::query()->insertOrIgnore($bookingAddresses->toArray());
        }
    }
};
