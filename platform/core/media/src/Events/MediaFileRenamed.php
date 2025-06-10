<?php

namespace Guestcms\Media\Events;

use Guestcms\Media\Models\MediaFile;
use Illuminate\Foundation\Events\Dispatchable;

class MediaFileRenamed
{
    use Dispatchable;

    public function __construct(public MediaFile $file)
    {
    }
}
