<?php

namespace Guestcms\Base\Events;

use Guestcms\Base\Contracts\PanelSections\PanelSection;
use Illuminate\Foundation\Events\Dispatchable;

class PanelSectionItemsRendered
{
    use Dispatchable;

    public function __construct(public PanelSection $section, public array $items, public string $content)
    {
    }
}
