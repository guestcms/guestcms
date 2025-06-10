<?php

namespace Guestcms\Theme\Facades;

use Guestcms\Theme\Manager as ManagerSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void registerTheme(array|string $theme)
 * @method static array getAllThemes()
 * @method static array getThemes()
 * @method static array getThemePresets(string $theme)
 *
 * @see \Guestcms\Theme\Manager
 */
class Manager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ManagerSupport::class;
    }
}
