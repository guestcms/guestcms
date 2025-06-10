<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Language\Models\LanguageMeta;
use Guestcms\Testimonial\Models\Testimonial;

class TestimonialSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('testimonials');

        $data = [
            [
                'name' => 'Adam Williams',
                'company' => 'CEO Of Microsoft',
            ],
            [
                'name' => 'Retha Deowalim',
                'company' => 'CEO Of Apple',
            ],
            [
                'name' => 'Sam J. Wasim',
                'company' => 'Pio Founder',
            ],
        ];

        Testimonial::query()->truncate();

        foreach ($data as $index => $item) {
            $item['image'] = 'testimonials/0' . ($index + 1) . '.png';
            $item['content'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua';

            $testimonial = Testimonial::query()->create($item);

            LanguageMeta::saveMetaData($testimonial);
        }
    }
}
