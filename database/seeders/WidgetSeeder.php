<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Theme\Facades\Theme;
use Guestcms\Widget\Models\Widget as WidgetModel;

class WidgetSeeder extends BaseSeeder
{
    public function run(): void
    {
        WidgetModel::query()->truncate();

        $data = [
            [
                'widget_id' => 'CustomMenuWidget',
                'sidebar_id' => 'footer_sidebar',
                'position' => 0,
                'data' => [
                    'id' => 'CustomMenuWidget',
                    'name' => 'Services.',
                    'menu_id' => 'services',
                ],
            ],
            [
                'widget_id' => 'RecentPostsWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 0,
                'data' => [
                    'id' => 'RecentPostsWidget',
                    'name' => 'Recent posts',
                    'number_display' => 5,
                ],
            ],
            [
                'widget_id' => 'BlogCategoriesWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 1,
                'data' => [
                    'id' => 'BlogCategoriesWidget',
                    'name' => 'Categories',
                    'number_display' => 5,
                ],
            ],
            [
                'widget_id' => 'BlogTagsWidget',
                'sidebar_id' => 'primary_sidebar',
                'position' => 2,
                'data' => [
                    'id' => 'BlogTagsWidget',
                    'name' => 'Popular Tags',
                    'number_display' => 5,
                ],
            ],
        ];

        $theme = Theme::getThemeName();

        foreach ($data as $item) {
            $item['theme'] = $theme;
            WidgetModel::query()->create($item);
        }
    }
}
