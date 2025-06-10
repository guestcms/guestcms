<?php

namespace Guestcms\Base\Facades;

use Guestcms\Base\Supports\Assets as BaseAssets;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void setConfig(array $config)
 * @method static string renderHeader($lastStyles = [])
 * @method static string renderFooter()
 * @method static \Guestcms\Base\Supports\Assets usingVueJS()
 * @method static \Guestcms\Base\Supports\Assets disableVueJS()
 * @method static bool hasVueJs()
 * @method static static addScripts(array|string $assets)
 * @method static static addStyles(array|string $assets)
 * @method static static addStylesDirectly(array|string $assets, array $attributes = [])
 * @method static static addScriptsDirectly(array|string $assets, string $location = 'footer', array $attributes = [])
 * @method static static removeStyles(array|string $assets)
 * @method static static removeScripts(array|string $assets)
 * @method static static removeItemDirectly(array|string $assets, string|null $location = null)
 * @method static array getScripts(string|null $location = null)
 * @method static array getStyles(array $lastStyles = [])
 * @method static string|null scriptToHtml(string $name)
 * @method static string|null styleToHtml(string $name)
 * @method static string getBuildVersion()
 * @method static \Guestcms\Assets\HtmlBuilder getHtmlBuilder()
 *
 * @see \Guestcms\Base\Supports\Assets
 */
class Assets extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseAssets::class;
    }
}
