<?php

namespace Guestcms\Media\Events;

use Guestcms\Media\Models\MediaFolder;
use Illuminate\Foundation\Events\Dispatchable;

class MediaFolderRenaming
{
    use Dispatchable;

    public function __construct(public MediaFolder $file, public string $newName, public bool $renameOnDisk)
    {
    }
}
