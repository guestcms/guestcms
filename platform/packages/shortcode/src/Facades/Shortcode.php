<?php

namespace Guestcms\Shortcode\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Guestcms\Shortcode\Shortcode register(string $key, string|null $name, string|null $description = null, $callback = null, string $previewImage = '')
 * @method static void remove(string $key)
 * @method static \Guestcms\Shortcode\Shortcode setPreviewImage(string $key, string $previewImage)
 * @method static \Guestcms\Shortcode\Shortcode enable()
 * @method static \Guestcms\Shortcode\Shortcode disable()
 * @method static \Illuminate\Support\HtmlString compile(string $value, bool $force = false)
 * @method static string|null strip(string|null $value)
 * @method static array getAll()
 * @method static void setAdminConfig(string $key, callable|array|string|null $html)
 * @method static void modifyAdminConfig(string $key, callable $callback)
 * @method static string generateShortcode(string $name, array $attributes = [], string|null $content = null, bool $lazy = false)
 * @method static \Guestcms\Shortcode\Compilers\ShortcodeCompiler getCompiler()
 * @method static \Guestcms\Shortcode\ShortcodeField fields()
 *
 * @see \Guestcms\Shortcode\Shortcode
 */
class Shortcode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'shortcode';
    }
}
