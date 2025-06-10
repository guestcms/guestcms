<?php

namespace Guestcms\Gallery\Models;

use Guestcms\Base\Models\BaseModel;

class GalleryMeta extends BaseModel
{
    protected $table = 'gallery_meta';

    protected $casts = [
        'images' => 'json',
    ];
}
