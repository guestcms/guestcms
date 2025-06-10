<?php

namespace Database\Seeders;

use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Hotel\Models\Tax;

class TaxSeeder extends BaseSeeder
{
    public function run(): void
    {
        Tax::query()->truncate();

        Tax::query()->create([
            'title' => 'VAT',
            'percentage' => 10,
            'priority' => 1,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        Tax::query()->create([
            'title' => 'None',
            'percentage' => 0,
            'priority' => 2,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);
    }
}
