<?php

namespace Database\Seeders;

use Guestcms\Base\Supports\BaseSeeder;
use Guestcms\Language\Models\LanguageMeta;
use Guestcms\Menu\Facades\Menu;
use Guestcms\Menu\Models\Menu as MenuModel;
use Guestcms\Menu\Models\MenuLocation;
use Guestcms\Menu\Models\MenuNode;
use Guestcms\Page\Models\Page;
use Illuminate\Support\Arr;

class MenuSeeder extends BaseSeeder
{
    public function run(): void
    {
        $data = [
            [
                'name' => 'Header menu',
                'slug' => 'header-menu',
                'location' => 'header-menu',
                'items' => [
                    [
                        'title' => 'Home',
                        'url' => '/',
                    ],
                    [
                        'title' => 'Rooms',
                        'url' => '/rooms',
                        'children' => [
                            [
                                'title' => 'Luxury Hall Of Fame',
                                'url' => '/rooms/luxury-hall-of-fame',
                            ],
                            [
                                'title' => 'Pendora Fame',
                                'url' => '/rooms/pendora-fame',
                            ],
                        ],
                    ],
                    [
                        'title' => 'News',
                        'reference_id' => 2,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Contact',
                        'reference_id' => 3,
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name' => 'Our pages',
                'slug' => 'our-pages',
                'location' => 'side-menu',
                'items' => [
                    [
                        'title' => 'About Us',
                        'reference_id' => 6,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Our Gallery',
                        'reference_id' => 5,
                        'reference_type' => Page::class,
                        'children' => [
                            [
                                'title' => 'King Bed',
                                'url' => '/galleries/king-bed',
                                'parent_id' => 8,
                            ],
                            [
                                'title' => 'Duplex Restaurant',
                                'url' => '/galleries/duplex-restaurant',
                                'parent_id' => 8,
                            ],
                        ],
                    ],
                    [
                        'title' => 'Restaurant',
                        'reference_id' => 4,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Places',
                        'reference_id' => 7,
                        'reference_type' => Page::class,
                    ],
                    [
                        'title' => 'Our Offers',
                        'reference_id' => 8,
                        'reference_type' => Page::class,
                    ],
                ],
            ],
            [
                'name' => 'Services.',
                'slug' => 'services',
                'items' => [
                    [
                        'title' => 'Restaurant & Bar',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Swimming Pool',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Restaurant',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Conference Room',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Cocktail Party Houses',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Gaming Zone',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Marriage Party',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Party Planning',
                        'url' => '#',
                    ],
                    [
                        'title' => 'Tour Consultancy',
                        'url' => '#',
                    ],
                ],
            ],
        ];

        MenuModel::query()->truncate();
        MenuLocation::query()->truncate();
        MenuNode::query()->truncate();

        foreach ($data as $index => $item) {
            $menu = MenuModel::query()->create(Arr::except($item, ['items', 'location']));

            if (isset($item['location'])) {
                $menuLocation = MenuLocation::query()->create([
                    'menu_id' => $menu->id,
                    'location' => $item['location'],
                ]);

                LanguageMeta::saveMetaData($menuLocation);
            }

            foreach ($item['items'] as $menuNode) {
                $this->createMenuNode($index, $menuNode, 'en_US', $menu->id);
            }

            LanguageMeta::saveMetaData($menu);
        }

        Menu::clearCacheMenuItems();
    }

    protected function createMenuNode(
        int $index,
        array $menuNode,
        string $locale,
        int|string $menuId,
        int|string $parentId = 0
    ): void {
        $menuNode['menu_id'] = $menuId;
        $menuNode['parent_id'] = $parentId;

        if (isset($menuNode['url'])) {
            $menuNode['url'] = str_replace(url(''), '', $menuNode['url']);
        }

        if (Arr::has($menuNode, 'children')) {
            $children = $menuNode['children'];
            $menuNode['has_child'] = true;

            unset($menuNode['children']);
        } else {
            $children = [];
            $menuNode['has_child'] = false;
        }

        $createdNode = MenuNode::query()->create($menuNode);

        if ($children) {
            foreach ($children as $child) {
                $this->createMenuNode($index, $child, $locale, $menuId, $createdNode->id);
            }
        }
    }
}
