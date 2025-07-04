<?php

namespace Guestcms\Theme;

use Illuminate\Support\Facades\URL;

class Breadcrumb
{
    public array $crumbs = [];

    public function enabled(): bool
    {
        return (bool) theme_option('theme_breadcrumb_enabled', 1) == 1;
    }

    public function add(string|array|null $label, ?string $url = ''): self
    {
        if (! $this->enabled()) {
            return $this;
        }

        if (is_array($label)) {
            if (count($label) > 0) {
                foreach ($label as $crumb) {
                    $defaults = [
                        'label' => '',
                        'url' => '',
                    ];
                    $crumb = array_merge($defaults, $crumb);
                    $this->add($crumb['label'], $crumb['url']);
                }
            }
        } else {
            $label = trim(strip_tags($label, '<i><b><strong>'));
            if (! preg_match('|^http(s)?|', $url)) {
                $url = URL::to($url);
            }

            $this->crumbs[] = ['label' => $label, 'url' => $url];
        }

        return $this;
    }

    public function render(string $view = 'packages/theme::partials.breadcrumb'): string
    {
        return view($view)->render();
    }

    public function getCrumbs(): array
    {
        if (! $this->enabled()) {
            return [];
        }

        return collect($this->crumbs)->unique('label')->toArray();
    }
}
