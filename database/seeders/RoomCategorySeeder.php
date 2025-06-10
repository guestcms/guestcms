<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Hotel\Models\RoomCategory;

class RoomCategorySeeder extends BaseSeeder
{
    public function run(): void
    {
        RoomCategory::query()->truncate();

        $roomCategories = [
            [
                'name' => 'Luxury',
            ],
            [
                'name' => 'Family',
            ],
            [
                'name' => 'Double Bed',
            ],
            [
                'name' => 'Relax',
            ],
        ];

        foreach ($roomCategories as $roomCategory) {
            $roomCategory['is_featured'] = true;

            RoomCategory::query()->create($roomCategory);
        }
    }
}
