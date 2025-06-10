<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Hotel\Models\FoodType;

class FoodTypeSeeder extends BaseSeeder
{
    public function run(): void
    {
        FoodType::query()->truncate();

        $foodTypes = [
            [
                'name' => 'Chicken',
                'icon' => 'flaticon-boiled',
            ],
            [
                'name' => 'Italian',
                'icon' => 'flaticon-pizza',
            ],
            [
                'name' => 'Coffee',
                'icon' => 'flaticon-coffee',
            ],
            [
                'name' => 'Bake Cake',
                'icon' => 'flaticon-cake',
            ],
            [
                'name' => 'Cookies',
                'icon' => 'flaticon-cookie',
            ],
            [
                'name' => 'Cocktail',
                'icon' => 'flaticon-cocktail',
            ],
        ];

        foreach ($foodTypes as $foodType) {
            FoodType::query()->create($foodType);
        }
    }
}
