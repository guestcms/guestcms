<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Gallery\Models\Gallery as GalleryModel;
use Guestcms\Gallery\Models\GalleryMeta;
use Guestcms\Slug\Facades\SlugHelper;
use Guestcms\Slug\Models\Slug;
use Illuminate\Support\Str;

class GallerySeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('galleries');

        GalleryModel::query()->truncate();
        GalleryMeta::query()->truncate();

        $galleries = [
            [
                'name' => 'Duplex Restaurant',
            ],
            [
                'name' => 'Luxury room',
            ],
            [
                'name' => 'Pacific Room',
            ],
            [
                'name' => 'Family Room',
            ],
            [
                'name' => 'King Bed',
            ],
            [
                'name' => 'Special Foods',
            ],
        ];

        $images = [];
        for ($i = 0; $i < 8; $i++) {
            $images[] = [
                'img' => 'galleries/0' . ($i + 1) . '.jpg',
                'description' => $this->fake()->text(150),
            ];
        }

        foreach ($galleries as $index => $item) {
            $item['description'] = $this->fake()->text(150);
            $item['image'] = 'galleries/0' . ($index + 1) . '.jpg';
            $item['user_id'] = 1;
            $item['is_featured'] = true;

            $gallery = GalleryModel::query()->create($item);

            Slug::query()->create([
                'reference_type' => GalleryModel::class,
                'reference_id' => $gallery->id,
                'key' => Str::slug($gallery->name),
                'prefix' => SlugHelper::getPrefix(GalleryModel::class),
            ]);

            GalleryMeta::query()->create([
                'images' => json_encode($images),
                'reference_id' => $gallery->id,
                'reference_type' => GalleryModel::class,
            ]);
        }
    }
}
