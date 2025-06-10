<?php

namespace Guestcms\Menu\Facades;

use Guestcms\Menu\Menu as BaseMenu;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool hasMenu(string $slug)
 * @method static array recursiveSaveMenu(array $menuNodes, string|int $menuId, string|int $parentId)
 * @method static \Guestcms\Menu\Models\MenuNode getReferenceMenuNode(array $item, \Guestcms\Menu\Models\MenuNode $menuNode)
 * @method static \Guestcms\Menu\Menu addMenuLocation(string $location, string $description)
 * @method static array getMenuLocations()
 * @method static \Guestcms\Menu\Menu removeMenuLocation(string $location)
 * @method static string|null renderMenuLocation(string $location, array $attributes = [])
 * @method static bool isLocationHasMenu(string $location)
 * @method static void load(bool $force = false)
 * @method static string|null generateMenu(array $args = [])
 * @method static void registerMenuOptions(string $model, string $name)
 * @method static string|null generateSelect(array $args = [])
 * @method static \Guestcms\Menu\Menu addMenuOptionModel(string $model)
 * @method static array getMenuOptionModels()
 * @method static \Guestcms\Menu\Menu setMenuOptionModels(array $models)
 * @method static \Guestcms\Menu\Menu clearCacheMenuItems()
 * @method static void useMenuItemIconImage()
 * @method static void saveMenuNodeImages(array $nodes, \Guestcms\Menu\Models\MenuNode $model)
 * @method static void useMenuItemBadge()
 * @method static void saveMenuNodeBadges(array $nodes, \Guestcms\Menu\Models\MenuNode $model)
 *
 * @see \Guestcms\Menu\Menu
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseMenu::class;
    }
}
