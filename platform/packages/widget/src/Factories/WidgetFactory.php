<?php

namespace Guestcms\Widget\Factories;

use Guestcms\Widget\Misc\InvalidWidgetClassException;
use Exception;
use Illuminate\Support\HtmlString;

class WidgetFactory extends AbstractWidgetFactory
{
    protected array $widgets = [];

    public function registerWidget(string $widget): WidgetFactory
    {
        $this->widgets[] = new $widget();

        return $this;
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }

    public function run(): HtmlString|string|null
    {
        $args = func_get_args();

        try {
            $this->instantiateWidget($args);
        } catch (InvalidWidgetClassException | Exception $exception) {
            return (app()->hasDebugModeEnabled() && ! app()->isProduction()) ? $exception->getMessage() : null;
        }

        return $this->convertToViewExpression($this->getContent());
    }

    protected function getContent(): ?string
    {
        $content = $this->app->call([$this->widget, 'run'], $this->widgetParams);

        return is_object($content) ? $content->__toString() : $content;
    }
}
