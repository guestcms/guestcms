<?php

namespace Guestcms\Base\Events;

use Illuminate\Foundation\Events\Dispatchable;

class SystemUpdateDownloaded
{
    use Dispatchable;

    public function __construct(public string $filePath)
    {
    }
}
