<?php

namespace Guestcms\Widget\Facades;

use Guestcms\Widget\WidgetGroup;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Guestcms\Widget\Factories\WidgetFactory registerWidget(string $widget)
 * @method static array getWidgets()
 * @method static \Illuminate\Support\HtmlString|string|null run()
 *
 * @see \Guestcms\Widget\Factories\WidgetFactory
 */
class Widget extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'guestcms.widget';
    }

    public static function group(string $name): WidgetGroup
    {
        return app('guestcms.widget-group-collection')->group($name);
    }
}
