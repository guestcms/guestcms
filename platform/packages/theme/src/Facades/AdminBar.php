<?php

namespace Guestcms\Theme\Facades;

use Guestcms\Theme\Supports\AdminBar as AdminBarSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool isDisplay()
 * @method static \Guestcms\Theme\Supports\AdminBar setIsDisplay(bool $isDisplay = true)
 * @method static array getGroups()
 * @method static array getLinksNoGroup()
 * @method static \Guestcms\Theme\Supports\AdminBar setLinksNoGroup(array $links)
 * @method static \Guestcms\Theme\Supports\AdminBar registerGroup(string $slug, string $title, string $link = 'javascript:;')
 * @method static \Guestcms\Theme\Supports\AdminBar registerLink(string $title, string $url, $group = null, string|null $permission = null)
 * @method static string render()
 *
 * @see \Guestcms\Theme\Supports\AdminBar
 */
class AdminBar extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return AdminBarSupport::class;
    }
}
