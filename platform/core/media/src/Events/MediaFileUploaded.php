<?php

namespace Guestcms\Media\Events;

use Guestcms\Media\Models\MediaFile;
use Illuminate\Foundation\Events\Dispatchable;

class MediaFileUploaded
{
    use Dispatchable;

    public function __construct(public MediaFile $file)
    {
    }
}
