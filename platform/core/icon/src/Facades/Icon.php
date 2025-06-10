<?php

namespace Guestcms\Icon\Facades;

use Guestcms\Icon\IconManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getDefaultDriver()
 * @method static \Guestcms\Icon\IconDriver createSvgDriver()
 * @method static mixed driver(string|null $driver = null)
 * @method static \Guestcms\Icon\IconManager extend(string $driver, \Closure $callback)
 * @method static array getDrivers()
 * @method static \Illuminate\Contracts\Container\Container getContainer()
 * @method static \Guestcms\Icon\IconManager setContainer(\Illuminate\Contracts\Container\Container $container)
 * @method static \Guestcms\Icon\IconManager forgetDrivers()
 * @method static static setConfig(array $config)
 * @method static array getConfig()
 * @method static array all()
 * @method static string render(string $name, array $attributes = [])
 * @method static bool has(string $name)
 * @method static static setIconPath(string $path)
 * @method static string iconPath()
 *
 * @see \Guestcms\Icon\IconManager
 * @see \Guestcms\Icon\IconDriver
 */
class Icon extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return IconManager::class;
    }
}
