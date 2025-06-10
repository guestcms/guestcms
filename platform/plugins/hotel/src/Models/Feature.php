<?php

namespace Guestcms\Hotel\Models;

use Guestcms\Base\Casts\SafeContent;
use Guestcms\Base\Enums\BaseStatusEnum;
use Guestcms\Base\Models\BaseModel;

class Feature extends BaseModel
{
    protected $table = 'ht_features';

    protected $fillable = [
        'name',
        'description',
        'icon',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'icon' => SafeContent::class,
    ];
}
