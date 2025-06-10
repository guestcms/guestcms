<?php

use Guestcms\Menu\Models\Menu;
use Guestcms\Menu\Models\MenuNode;
use Guestcms\Page\Models\Page;

return [
    'supported' => [
        Page::class,
        Menu::class,
        MenuNode::class,
    ],
];
