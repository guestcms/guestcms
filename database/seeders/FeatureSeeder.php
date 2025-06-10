<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Hotel\Models\Feature;

class FeatureSeeder extends BaseSeeder
{
    public function run(): void
    {
        Feature::query()->truncate();

        $features = [
            [
                'name' => 'Have High Rating',
                'icon' => 'flaticon-rating',
                'is_featured' => true,
                'description' => 'Watch Video',
            ],
            [
                'name' => 'Quiet Hours',
                'icon' => 'flaticon-clock',
                'is_featured' => true,
                'description' => 'Watch Video',
            ],
            [
                'name' => 'Best Locations',
                'icon' => 'flaticon-location-pin',
                'is_featured' => true,
                'description' => 'Watch Video',
            ],
            [
                'name' => 'Free Cancellation',
                'icon' => 'flaticon-clock-1',
                'description' => 'Watch Video',
            ],
            [
                'name' => 'Payment Options',
                'icon' => 'flaticon-credit-card',
                'description' => 'Watch Video',
            ],
            [
                'name' => 'Special Offers',
                'icon' => 'flaticon-discount',
                'description' => 'Watch Video',
            ],
        ];

        foreach ($features as $feature) {
            Feature::query()->create($feature);
        }
    }
}
