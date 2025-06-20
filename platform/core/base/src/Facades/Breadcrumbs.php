<?php

namespace Guestcms\Base\Facades;

use Guestcms\Base\Supports\BreadcrumbsManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void register(string $name, callable $callback, bool $modify = false)
 * @method static void for(string $name, callable $callback, bool $modify = false)
 * @method static void before(callable $callback)
 * @method static void after(callable $callback)
 * @method static bool exists(string|null $name = null)
 * @method static string render(string|null $name = null, ...$params)
 * @method static \Illuminate\Support\HtmlString view(string $view, string|null $name = null, ...$params)
 * @method static \Illuminate\Support\Collection generate(string|null $name = null, ...$params)
 * @method static \stdClass|null current()
 * @method static void setCurrentRoute(string $name, ...$params)
 * @method static void clearCurrentRoute()
 * @method static void macro(string $name, object|callable $macro, object|callable $macro = null)
 * @method static void mixin(object $mixin, bool $replace = true)
 * @method static bool hasMacro(string $name)
 * @method static void flushMacros()
 *
 * @see \Guestcms\Base\Supports\BreadcrumbsManager
 */
class Breadcrumbs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BreadcrumbsManager::class;
    }
}
