<?php

namespace Guestcms\Newsletter\Facades;

use Guestcms\Newsletter\Contracts\Factory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string getDefaultDriver()
 * @method static void registerNewsletterPopup(bool $keepHtmlDomOnClose = false)
 * @method static mixed driver(string|null $driver = null)
 * @method static \Guestcms\Newsletter\NewsletterManager extend(string $driver, \Closure $callback)
 * @method static array getDrivers()
 * @method static \Illuminate\Contracts\Container\Container getContainer()
 * @method static \Guestcms\Newsletter\NewsletterManager setContainer(\Illuminate\Contracts\Container\Container $container)
 * @method static \Guestcms\Newsletter\NewsletterManager forgetDrivers()
 *
 * @see \Guestcms\Newsletter\NewsletterManager
 */
class Newsletter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Factory::class;
    }
}
