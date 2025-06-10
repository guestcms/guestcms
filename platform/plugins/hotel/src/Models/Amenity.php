<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;

class Amenity extends BaseModel
{
    protected $table = 'ht_amenities';

    protected $fillable = [
        'name',
        'icon',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'icon' => SafeContent::class,
    ];
}
