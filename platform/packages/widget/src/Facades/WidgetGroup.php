<?php

namespace Guestcms\Widget\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Guestcms\Widget\WidgetGroup group(string $sidebarId)
 * @method static \Guestcms\Widget\WidgetGroupCollection setGroup(array $args)
 * @method static \Guestcms\Widget\WidgetGroupCollection removeGroup(string $groupId)
 * @method static array getGroups()
 * @method static string render(string $sidebarId)
 * @method static void load(bool $force = false)
 * @method static \Illuminate\Support\Collection getData()
 *
 * @see \Guestcms\Widget\WidgetGroupCollection
 */
class WidgetGroup extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'guestcms.widget-group-collection';
    }
}
