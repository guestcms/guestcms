<?php

namespace Guestcms\Theme\Supports;

use Guestcms\Base\Facades\BaseHelper;
use Guestcms\Base\Facades\Html;
use Guestcms\Media\Facades\RvMedia;
use Illuminate\Support\HtmlString;

class SocialLink
{
    public function __construct(
        protected ?string $name,
        protected ?string $url,
        protected ?string $icon,
        protected ?string $image,
        protected ?string $color,
        protected ?string $backgroundColor
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getColor(): ?string
    {
        if ($this->color === 'transparent') {
            return null;
        }

        return $this->color;
    }

    public function getBackgroundColor(): ?string
    {
        if ($this->backgroundColor === 'transparent') {
            return null;
        }

        return $this->backgroundColor;
    }

    public function getAttributes(array $attributes = []): HtmlString
    {
        $attributes = [
            'href' => $this->getUrl(),
            'title' => $this->getName(),
            'target' => '_blank',
            ...$attributes,
        ];

        $styles = [];

        if ($backgroundColor = $this->getBackgroundColor()) {
            $styles[] = sprintf('background-color: %s !important;', $backgroundColor);
        }

        if ($color = $this->getColor()) {
            $styles[] = sprintf('color: %s !important;', $color);
        }

        if ($styles) {
            $attributes['style'] = implode(' ', $styles);
        }

        return new HtmlString(Html::attributes($attributes));
    }

    public function getIconHtml(array $attributes = []): ?HtmlString
    {
        if ($this->image) {

            $attributes = [
                'loading' => false,
                ...$attributes,
            ];

            return RvMedia::image($this->image, $this->name, attributes: $attributes);
        }

        if (! $this->icon) {
            return null;
        }

        if (BaseHelper::hasIcon($this->icon)) {
            $color = $this->getColor();

            $attributes['style'] = ($color ? sprintf('color: %s !important;', $color) : null);

            $icon = BaseHelper::renderIcon($this->icon, attributes: $attributes);
        } else {
            $icon = BaseHelper::clean(sprintf('<i class="%s"></i>', $this->icon));
        }

        return new HtmlString($icon);
    }
}
