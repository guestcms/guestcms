<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;

class Place extends BaseModel
{
    protected $table = 'ht_places';

    protected $fillable = [
        'name',
        'distance',
        'description',
        'content',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'distance' => SafeContent::class,
        'description' => SafeContent::class,
        'content' => SafeContent::class,
    ];
}
