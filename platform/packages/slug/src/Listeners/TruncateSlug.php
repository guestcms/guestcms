<?php

namespace Guestcms\Slug\Listeners;

use Guestcms\Slug\Models\Slug;

class TruncateSlug
{
    public function handle(): void
    {
        Slug::query()->truncate();
    }
}
