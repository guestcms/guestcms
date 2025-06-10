<?php

use Guestcms\Menu\Facades\Menu;

app()->booted(fn () => Menu::useMenuItemIconImage());
